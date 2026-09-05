<?php

session_start();

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| CHECK ADMIN LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["user_id"]) ||
    ($_SESSION["role"] ?? "") !== "admin"
) {

    header("Location: admin-login.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| ADD DRIVER
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["action"]) &&
    $_POST["action"] === "add"
) {

    $name = trim($_POST["name"] ?? "");
    $driver_id = trim($_POST["driver_id"] ?? "");
    $department = trim($_POST["department"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    if (
        $name === "" ||
        $email === "" ||
        $password === ""
    ) {

        header(
            "Location: drivers.php?error=" .
            urlencode("Name, email and password are required.")
        );

        exit;

    }


    /*
    |----------------------------------------------------------------------
    | CHECK EMAIL
    |----------------------------------------------------------------------
    */

    $check_stmt = $conn->prepare(
        "SELECT id FROM users WHERE email = ? LIMIT 1"
    );

    $check_stmt->bind_param(
        "s",
        $email
    );

    $check_stmt->execute();

    $check_result = $check_stmt->get_result();


    if ($check_result->num_rows > 0) {

        $check_stmt->close();

        header(
            "Location: drivers.php?error=" .
            urlencode("This email is already registered.")
        );

        exit;

    }

    $check_stmt->close();


    /*
    |----------------------------------------------------------------------
    | CHECK DRIVER ID
    |----------------------------------------------------------------------
    |
    | student_id is the existing ID field in your users table.
    | We use it as Driver ID for driver accounts.
    |
    */

    if ($driver_id !== "") {

        $check_id_stmt = $conn->prepare(
            "SELECT id FROM users WHERE student_id = ? LIMIT 1"
        );

        $check_id_stmt->bind_param(
            "s",
            $driver_id
        );

        $check_id_stmt->execute();

        $check_id_result =
            $check_id_stmt->get_result();


        if ($check_id_result->num_rows > 0) {

            $check_id_stmt->close();

            header(
                "Location: drivers.php?error=" .
                urlencode("This Driver ID is already registered.")
            );

            exit;

        }

        $check_id_stmt->close();

    }


    /*
    |----------------------------------------------------------------------
    | HASH PASSWORD
    |----------------------------------------------------------------------
    */

    $hashed_password =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );


    /*
    |----------------------------------------------------------------------
    | INSERT DRIVER
    |----------------------------------------------------------------------
    */

    $insert_stmt = $conn->prepare("

        INSERT INTO users
        (
            name,
            student_id,
            department,
            email,
            password,
            role
        )

        VALUES
        (
            ?,
            NULLIF(?, ''),
            NULLIF(?, ''),
            ?,
            ?,
            'driver'
        )

    ");


    if (!$insert_stmt) {

        die(
            "Add driver query error: " .
            $conn->error
        );

    }


    $insert_stmt->bind_param(
        "sssss",
        $name,
        $driver_id,
        $department,
        $email,
        $hashed_password
    );


    if ($insert_stmt->execute()) {

        $insert_stmt->close();

        header(
            "Location: drivers.php?success=" .
            urlencode("Driver added successfully.")
        );

        exit;

    }


    $insert_stmt->close();


    header(
        "Location: drivers.php?error=" .
        urlencode("Failed to add driver.")
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| EDIT DRIVER
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["action"]) &&
    $_POST["action"] === "edit"
) {

    $driver_id_db =
        (int) ($_POST["id"] ?? 0);

    $name =
        trim($_POST["name"] ?? "");

    $driver_code =
        trim($_POST["driver_id"] ?? "");

    $department =
        trim($_POST["department"] ?? "");

    $email =
        trim($_POST["email"] ?? "");

    $password =
        $_POST["password"] ?? "";


    if (
        $driver_id_db <= 0 ||
        $name === "" ||
        $email === ""
    ) {

        header(
            "Location: drivers.php?error=" .
            urlencode("Name and email are required.")
        );

        exit;

    }


    /*
    |----------------------------------------------------------------------
    | CHECK EMAIL BELONGS TO ANOTHER USER
    |----------------------------------------------------------------------
    */

    $check_stmt = $conn->prepare("

        SELECT id

        FROM users

        WHERE email = ?

        AND id != ?

        LIMIT 1

    ");

    $check_stmt->bind_param(
        "si",
        $email,
        $driver_id_db
    );

    $check_stmt->execute();

    $check_result =
        $check_stmt->get_result();


    if ($check_result->num_rows > 0) {

        $check_stmt->close();

        header(
            "Location: drivers.php?error=" .
            urlencode("This email is already registered.")
        );

        exit;

    }

    $check_stmt->close();


    /*
    |----------------------------------------------------------------------
    | CHECK DRIVER ID
    |----------------------------------------------------------------------
    */

    if ($driver_code !== "") {

        $check_id_stmt = $conn->prepare("

            SELECT id

            FROM users

            WHERE student_id = ?

            AND id != ?

            LIMIT 1

        ");

        $check_id_stmt->bind_param(
            "si",
            $driver_code,
            $driver_id_db
        );

        $check_id_stmt->execute();

        $check_id_result =
            $check_id_stmt->get_result();


        if ($check_id_result->num_rows > 0) {

            $check_id_stmt->close();

            header(
                "Location: drivers.php?error=" .
                urlencode("This Driver ID is already registered.")
            );

            exit;

        }

        $check_id_stmt->close();

    }


    /*
    |----------------------------------------------------------------------
    | UPDATE WITHOUT PASSWORD
    |----------------------------------------------------------------------
    */

    if ($password === "") {

        $update_stmt = $conn->prepare("

            UPDATE users

            SET
                name = ?,
                student_id = NULLIF(?, ''),
                department = NULLIF(?, ''),
                email = ?

            WHERE id = ?

            AND role = 'driver'

        ");


        $update_stmt->bind_param(
            "ssssi",
            $name,
            $driver_code,
            $department,
            $email,
            $driver_id_db
        );

    }


    /*
    |----------------------------------------------------------------------
    | UPDATE WITH NEW PASSWORD
    |----------------------------------------------------------------------
    */

    else {

        $hashed_password =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );


        $update_stmt = $conn->prepare("

            UPDATE users

            SET
                name = ?,
                student_id = NULLIF(?, ''),
                department = NULLIF(?, ''),
                email = ?,
                password = ?

            WHERE id = ?

            AND role = 'driver'

        ");


        $update_stmt->bind_param(
            "sssssi",
            $name,
            $driver_code,
            $department,
            $email,
            $hashed_password,
            $driver_id_db
        );

    }


    if ($update_stmt->execute()) {

        $update_stmt->close();

        header(
            "Location: drivers.php?success=" .
            urlencode("Driver updated successfully.")
        );

        exit;

    }


    $update_stmt->close();


    header(
        "Location: drivers.php?error=" .
        urlencode("Failed to update driver.")
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DELETE DRIVER
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["action"]) &&
    $_POST["action"] === "delete"
) {

    $driver_id_db =
        (int) ($_POST["id"] ?? 0);


    if ($driver_id_db <= 0) {

        header(
            "Location: drivers.php?error=" .
            urlencode("Invalid driver.")
        );

        exit;

    }


    /*
    |----------------------------------------------------------------------
    | CHECK WHETHER DRIVER IS USED BY A SCHEDULE
    |----------------------------------------------------------------------
    */

    $schedule_check =
        $conn->prepare("

            SELECT COUNT(*) AS total

            FROM schedules

            WHERE driver_id = ?

        ");


    if ($schedule_check) {

        $schedule_check->bind_param(
            "i",
            $driver_id_db
        );

        $schedule_check->execute();

        $schedule_result =
            $schedule_check->get_result();

        $schedule_data =
            $schedule_result->fetch_assoc();

        $schedule_check->close();


        if (
            ($schedule_data["total"] ?? 0) > 0
        ) {

            header(
                "Location: drivers.php?error=" .
                urlencode(
                    "This driver is assigned to a schedule and cannot be deleted."
                )
            );

            exit;

        }

    }


    /*
    |----------------------------------------------------------------------
    | DELETE
    |----------------------------------------------------------------------
    */

    $delete_stmt = $conn->prepare("

        DELETE FROM users

        WHERE id = ?

        AND role = 'driver'

    ");


    $delete_stmt->bind_param(
        "i",
        $driver_id_db
    );


    if ($delete_stmt->execute()) {

        $delete_stmt->close();

        header(
            "Location: drivers.php?success=" .
            urlencode("Driver deleted successfully.")
        );

        exit;

    }


    $delete_stmt->close();


    header(
        "Location: drivers.php?error=" .
        urlencode("Failed to delete driver.")
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VIEW DRIVER
|--------------------------------------------------------------------------
*/

$view_driver = null;


if (
    isset($_GET["view"]) &&
    is_numeric($_GET["view"])
) {

    $view_id =
        (int) $_GET["view"];


    $view_stmt = $conn->prepare("

        SELECT
            id,
            name,
            student_id,
            department,
            email,
            created_at

        FROM users

        WHERE id = ?

        AND role = 'driver'

        LIMIT 1

    ");


    $view_stmt->bind_param(
        "i",
        $view_id
    );

    $view_stmt->execute();

    $view_result =
        $view_stmt->get_result();


    if (
        $view_result->num_rows === 1
    ) {

        $view_driver =
            $view_result->fetch_assoc();

    }


    $view_stmt->close();

}


/*
|--------------------------------------------------------------------------
| GET DRIVERS
|--------------------------------------------------------------------------
*/

$sql = "

    SELECT
        id,
        name,
        student_id,
        department,
        email,
        created_at

    FROM users

    WHERE role = 'driver'

    ORDER BY id DESC

";


$result =
    $conn->query($sql);


if (!$result) {

    die(
        "Driver query error: " .
        $conn->error
    );

}


/*
|--------------------------------------------------------------------------
| COUNT DRIVERS
|--------------------------------------------------------------------------
*/

$count_sql = "

    SELECT COUNT(*) AS total

    FROM users

    WHERE role = 'driver'

";


$count_result =
    $conn->query($count_sql);


$total_drivers = 0;


if ($count_result) {

    $count_row =
        $count_result->fetch_assoc();

    $total_drivers =
        $count_row["total"];

}


/*
|--------------------------------------------------------------------------
| EDIT DRIVER DATA
|--------------------------------------------------------------------------
*/

$edit_driver = null;


if (
    isset($_GET["edit"]) &&
    is_numeric($_GET["edit"])
) {

    $edit_id =
        (int) $_GET["edit"];


    $edit_stmt = $conn->prepare("

        SELECT
            id,
            name,
            student_id,
            department,
            email

        FROM users

        WHERE id = ?

        AND role = 'driver'

        LIMIT 1

    ");


    $edit_stmt->bind_param(
        "i",
        $edit_id
    );

    $edit_stmt->execute();

    $edit_result =
        $edit_stmt->get_result();


    if (
        $edit_result->num_rows === 1
    ) {

        $edit_driver =
            $edit_result->fetch_assoc();

    }


    $edit_stmt->close();

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Drivers | Admin | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >


    <style>

        /* =====================================================
           DRIVER PAGE EXTRA STYLES
        ===================================================== */

        .driver-toolbar-actions {

            display: flex;

            gap: 10px;

            align-items: center;

        }


        .admin-btn {

            border: none;

            padding: 10px 16px;

            border-radius: 8px;

            cursor: pointer;

            font-size: 14px;

            font-weight: 600;

            text-decoration: none;

            display: inline-block;

        }


        .admin-btn-primary {

            background: #1769e0;

            color: #ffffff;

        }


        .admin-btn-primary:hover {

            background: #1257bd;

        }


        .admin-btn-secondary {

            background: #eef2f7;

            color: #344054;

        }


        .admin-btn-secondary:hover {

            background: #e2e8f0;

        }


        .admin-btn-danger {

            background: #fee4e2;

            color: #b42318;

        }


        .admin-btn-danger:hover {

            background: #fecdca;

        }


        .action-buttons {

            display: flex;

            gap: 7px;

            flex-wrap: wrap;

        }


        .action-buttons a,
        .action-buttons button {

            border: none;

            padding: 7px 10px;

            border-radius: 7px;

            cursor: pointer;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

        }


        .view-button {

            background: #eef4ff;

            color: #175cd3;

        }


        .edit-button {

            background: #ecfdf3;

            color: #027a48;

        }


        .delete-button {

            background: #fef3f2;

            color: #b42318;

        }


        .driver-form-card {

            background: #ffffff;

            border: 1px solid #e4e7ec;

            border-radius: 14px;

            padding: 25px;

            margin-bottom: 25px;

        }


        .driver-form-card h2 {

            margin-bottom: 6px;

            color: #172033;

        }


        .driver-form-card > p {

            color: #667085;

            margin-bottom: 20px;

            font-size: 14px;

        }


        .form-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 18px;

        }


        .form-group {

            display: flex;

            flex-direction: column;

            gap: 7px;

        }


        .form-group.full {

            grid-column: 1 / -1;

        }


        .form-group label {

            font-size: 13px;

            font-weight: 600;

            color: #344054;

        }


        .form-group input {

            width: 100%;

            padding: 11px 12px;

            border: 1px solid #d0d5dd;

            border-radius: 8px;

            outline: none;

            font-size: 14px;

        }


        .form-group input:focus {

            border-color: #1769e0;

        }


        .form-actions {

            margin-top: 20px;

            display: flex;

            gap: 10px;

        }


        .message {

            padding: 12px 15px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

        }


        .message-success {

            background: #ecfdf3;

            color: #027a48;

            border: 1px solid #abefc6;

        }


        .message-error {

            background: #fef3f2;

            color: #b42318;

            border: 1px solid #fecdca;

        }


        .driver-view-card {

            background: #ffffff;

            border: 1px solid #e4e7ec;

            border-radius: 14px;

            padding: 25px;

            margin-bottom: 25px;

        }


        .driver-view-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 18px;

            margin-top: 20px;

        }


        .view-item {

            padding: 15px;

            background: #f8fafc;

            border-radius: 10px;

        }


        .view-item span {

            display: block;

            font-size: 12px;

            color: #98a2b3;

            margin-bottom: 5px;

        }


        .view-item strong {

            color: #344054;

            font-size: 14px;

        }


        .empty-message {

            color: #667085;

            font-size: 14px;

        }


        @media (max-width: 700px) {

            .form-grid,
            .driver-view-grid {

                grid-template-columns: 1fr;

            }

            .form-group.full {

                grid-column: auto;

            }

            .driver-toolbar-actions {

                margin-top: 15px;

            }

        }

    </style>

</head>


<body>


<div class="admin-layout">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="admin-sidebar">


        <div class="admin-logo">

            🚌 UniBus

        </div>


        <p class="admin-role">

            Administration

        </p>


        <nav>


            <a href="admin-dashboard.php">

                📊 Dashboard

            </a>


            <a href="students.php">

                👨‍🎓 Students

            </a>


            <a
                href="drivers.php"
                class="active"
            >

                🧑‍✈️ Drivers

            </a>


            <a href="buses.php">

                🚌 Buses

            </a>


            <a href="routes.php">

                🛣️ Routes

            </a>


            <a href="schedules.php">

                🕐 Schedules

            </a>


            <a href="bookings.php">

                🎫 Bookings

            </a>


            <a href="notices.php">

                📢 Notices

            </a>


        </nav>


        <a
            href="admin-logout.php"
            class="admin-logout"
        >

            ↪ Logout

        </a>


    </aside>



    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="admin-main">


        <!-- HEADER -->

        <header class="admin-header">


            <div>

                <h1>

                    Drivers

                </h1>


                <p>

                    Manage UniBus drivers

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 System Admin

            </div>


        </header>



        <!-- =================================================
             MESSAGES
        ================================================== -->

        <?php if (isset($_GET["success"])): ?>

            <div class="message message-success">

                ✅

                <?= htmlspecialchars(
                    $_GET["success"]
                ) ?>

            </div>

        <?php endif; ?>


        <?php if (isset($_GET["error"])): ?>

            <div class="message message-error">

                ❌

                <?= htmlspecialchars(
                    $_GET["error"]
                ) ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             VIEW DRIVER
        ================================================== -->

        <?php if ($view_driver): ?>


            <section class="driver-view-card">


                <h2>

                    Driver Information

                </h2>


                <p class="empty-message">

                    Details of the selected driver.

                </p>


                <div class="driver-view-grid">


                    <div class="view-item">

                        <span>

                            Driver ID

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $view_driver["student_id"]
                                ?: "-"
                            ) ?>

                        </strong>

                    </div>


                    <div class="view-item">

                        <span>

                            Name

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $view_driver["name"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="view-item">

                        <span>

                            Email

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $view_driver["email"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="view-item">

                        <span>

                            Department

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $view_driver["department"]
                                ?: "-"
                            ) ?>

                        </strong>

                    </div>


                    <div class="view-item">

                        <span>

                            Account Role

                        </span>

                        <strong>

                            Driver

                        </strong>

                    </div>


                    <div class="view-item">

                        <span>

                            Joined

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                date(
                                    "M d, Y",
                                    strtotime(
                                        $view_driver["created_at"]
                                    )
                                )
                            ) ?>

                        </strong>

                    </div>


                </div>


                <div class="form-actions">

                    <a
                        href="drivers.php"
                        class="admin-btn admin-btn-secondary"
                    >

                        ← Back to Drivers

                    </a>


                    <a
                        href="drivers.php?edit=<?= $view_driver["id"] ?>"
                        class="admin-btn admin-btn-primary"
                    >

                        ✏️ Edit Driver

                    </a>

                </div>


            </section>


        <?php endif; ?>



        <!-- =================================================
             ADD / EDIT DRIVER FORM
        ================================================== -->

        <?php if (
            isset($_GET["add"]) ||
            $edit_driver
        ): ?>


            <section class="driver-form-card">


                <?php if ($edit_driver): ?>


                    <h2>

                        ✏️ Edit Driver

                    </h2>


                    <p>

                        Update the driver's information below.

                    </p>


                    <form
                        method="POST"
                        action="drivers.php"
                    >


                        <input
                            type="hidden"
                            name="action"
                            value="edit"
                        >


                        <input
                            type="hidden"
                            name="id"
                            value="<?= $edit_driver["id"] ?>"
                        >


                        <div class="form-grid">


                            <div class="form-group">

                                <label>

                                    Driver Name *

                                </label>


                                <input
                                    type="text"
                                    name="name"
                                    value="<?= htmlspecialchars(
                                        $edit_driver["name"]
                                    ) ?>"
                                    required
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Driver ID

                                </label>


                                <input
                                    type="text"
                                    name="driver_id"
                                    value="<?= htmlspecialchars(
                                        $edit_driver["student_id"]
                                        ?? ""
                                    ) ?>"
                                    placeholder="Example: DRV-001"
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Department

                                </label>


                                <input
                                    type="text"
                                    name="department"
                                    value="<?= htmlspecialchars(
                                        $edit_driver["department"]
                                        ?? ""
                                    ) ?>"
                                    placeholder="Example: Transport"
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Email *

                                </label>


                                <input
                                    type="email"
                                    name="email"
                                    value="<?= htmlspecialchars(
                                        $edit_driver["email"]
                                    ) ?>"
                                    required
                                >

                            </div>



                            <div class="form-group full">

                                <label>

                                    New Password

                                </label>


                                <input
                                    type="password"
                                    name="password"
                                    placeholder="Leave blank to keep current password"
                                >

                            </div>


                        </div>


                        <div class="form-actions">


                            <button
                                type="submit"
                                class="admin-btn admin-btn-primary"
                            >

                                💾 Update Driver

                            </button>


                            <a
                                href="drivers.php"
                                class="admin-btn admin-btn-secondary"
                            >

                                Cancel

                            </a>


                        </div>


                    </form>


                <?php else: ?>


                    <h2>

                        ➕ Add New Driver

                    </h2>


                    <p>

                        Create a new driver account for UniBus.

                    </p>


                    <form
                        method="POST"
                        action="drivers.php"
                    >


                        <input
                            type="hidden"
                            name="action"
                            value="add"
                        >


                        <div class="form-grid">


                            <div class="form-group">

                                <label>

                                    Driver Name *

                                </label>


                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Example: Driver One"
                                    required
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Driver ID

                                </label>


                                <input
                                    type="text"
                                    name="driver_id"
                                    placeholder="Example: DRV-001"
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Department

                                </label>


                                <input
                                    type="text"
                                    name="department"
                                    placeholder="Example: Transport"
                                >

                            </div>



                            <div class="form-group">

                                <label>

                                    Email *

                                </label>


                                <input
                                    type="email"
                                    name="email"
                                    placeholder="driver@example.com"
                                    required
                                >

                            </div>



                            <div class="form-group full">

                                <label>

                                    Password *

                                </label>


                                <input
                                    type="password"
                                    name="password"
                                    placeholder="Enter driver password"
                                    required
                                >

                            </div>


                        </div>


                        <div class="form-actions">


                            <button
                                type="submit"
                                class="admin-btn admin-btn-primary"
                            >

                                ➕ Add Driver

                            </button>


                            <a
                                href="drivers.php"
                                class="admin-btn admin-btn-secondary"
                            >

                                Cancel

                            </a>


                        </div>


                    </form>


                <?php endif; ?>


            </section>


        <?php endif; ?>



        <!-- =================================================
             DRIVER STAT
        ================================================== -->

        <section class="admin-stats">


            <div class="admin-stat-card">


                <span class="stat-icon">

                    🧑‍✈️

                </span>


                <div>

                    <p>

                        Total Drivers

                    </p>


                    <h2>

                        <?= $total_drivers ?>

                    </h2>

                </div>


            </div>


        </section>



        <!-- =================================================
             DRIVER TABLE
        ================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">


                <div>

                    <h2>

                        Driver List

                    </h2>


                    <p>

                        Drivers registered in the UniBus system

                    </p>

                </div>


                <div class="driver-toolbar-actions">


                    <a
                        href="drivers.php?add=1"
                        class="admin-btn admin-btn-primary"
                    >

                        + Add Driver

                    </a>


                </div>


            </div>



            <div class="admin-table-container">


                <table class="admin-table">


                    <thead>


                        <tr>


                            <th>

                                ID

                            </th>


                            <th>

                                Driver Name

                            </th>


                            <th>

                                Email

                            </th>


                            <th>

                                Driver ID

                            </th>


                            <th>

                                Joined

                            </th>


                            <th>

                                Status

                            </th>


                            <th>

                                Actions

                            </th>


                        </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        $result->num_rows === 0
                    ): ?>


                        <tr>


                            <td
                                colspan="7"
                                class="no-data"
                            >


                                <div>

                                    🧑‍✈️

                                </div>


                                <strong>

                                    No drivers found

                                </strong>


                                <p>

                                    Add your first driver.

                                </p>


                            </td>


                        </tr>


                    <?php else: ?>


                        <?php while (
                            $driver =
                            $result->fetch_assoc()
                        ): ?>


                            <tr>


                                <td>

                                    <?= htmlspecialchars(
                                        $driver["id"]
                                    ) ?>

                                </td>


                                <td>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $driver["name"]
                                        ) ?>

                                    </strong>


                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $driver["email"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $driver["student_id"]
                                        ?: "-"
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $driver["created_at"]
                                            )
                                        )
                                    ) ?>

                                </td>


                                <td>

                                    <span class="status confirmed">

                                        ● Active

                                    </span>

                                </td>


                                <td>


                                    <div class="action-buttons">


                                        <a
                                            href="drivers.php?view=<?= $driver["id"] ?>"
                                            class="view-button"
                                        >

                                            👁 View

                                        </a>


                                        <a
                                            href="drivers.php?edit=<?= $driver["id"] ?>"
                                            class="edit-button"
                                        >

                                            ✏ Edit

                                        </a>


                                        <form
                                            method="POST"
                                            action="drivers.php"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this driver?');"
                                        >


                                            <input
                                                type="hidden"
                                                name="action"
                                                value="delete"
                                            >


                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $driver["id"] ?>"
                                            >


                                            <button
                                                type="submit"
                                                class="delete-button"
                                            >

                                                🗑 Delete

                                            </button>


                                        </form>


                                    </div>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </section>


    </main>


</div>


</body>

</html>


<?php

$conn->close();

?>