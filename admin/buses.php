<?php

session_start();

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| CHECK ADMIN LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {

    header("Location: admin-login.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| VARIABLES
|--------------------------------------------------------------------------
*/

$message = "";
$error = "";

$edit_mode = false;

$edit_bus_id = "";
$edit_bus_number = "";
$edit_bus_name = "";
$edit_total_seats = "";
$edit_status = "active";


/*
|--------------------------------------------------------------------------
| ADD BUS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_bus"])) {

    $bus_number = trim($_POST["bus_number"] ?? "");
    $bus_name = trim($_POST["bus_name"] ?? "");
    $total_seats = trim($_POST["total_seats"] ?? "");
    $status = $_POST["status"] ?? "active";


    if ($bus_number === "") {

        $error = "Bus number is required.";

    } elseif ($total_seats === "" || !is_numeric($total_seats)) {

        $error = "Please enter a valid number of seats.";

    } elseif ((int)$total_seats <= 0) {

        $error = "Total seats must be greater than 0.";

    } elseif (!in_array($status, ["active", "inactive"])) {

        $error = "Invalid bus status.";

    } else {


        /*
        |------------------------------------------------------------------
        | CHECK DUPLICATE BUS NUMBER
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM buses
            WHERE bus_number = ?
            LIMIT 1
        ");

        $check_stmt->bind_param(
            "s",
            $bus_number
        );

        $check_stmt->execute();

        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {

            $error = "This bus number already exists.";

        }

        $check_stmt->close();


        /*
        |------------------------------------------------------------------
        | INSERT
        |------------------------------------------------------------------
        */

        if ($error === "") {

            $insert_stmt = $conn->prepare("
                INSERT INTO buses
                (
                    bus_number,
                    bus_name,
                    total_seats,
                    status
                )
                VALUES (?, ?, ?, ?)
            ");

            if (!$insert_stmt) {

                $error = "Database error: " . $conn->error;

            } else {

                $seats = (int)$total_seats;

                $insert_stmt->bind_param(
                    "ssis",
                    $bus_number,
                    $bus_name,
                    $seats,
                    $status
                );

                if ($insert_stmt->execute()) {

                    $message = "Bus added successfully.";

                } else {

                    $error =
                        "Failed to add bus: "
                        . $insert_stmt->error;

                }

                $insert_stmt->close();

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| UPDATE BUS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_bus"])) {

    $id = (int)($_POST["bus_id"] ?? 0);

    $bus_number = trim($_POST["bus_number"] ?? "");
    $bus_name = trim($_POST["bus_name"] ?? "");
    $total_seats = trim($_POST["total_seats"] ?? "");
    $status = $_POST["status"] ?? "active";


    if ($id <= 0) {

        $error = "Invalid bus ID.";

    } elseif ($bus_number === "") {

        $error = "Bus number is required.";

    } elseif ($total_seats === "" || !is_numeric($total_seats)) {

        $error = "Please enter a valid number of seats.";

    } elseif ((int)$total_seats <= 0) {

        $error = "Total seats must be greater than 0.";

    } elseif (!in_array($status, ["active", "inactive"])) {

        $error = "Invalid bus status.";

    } else {


        /*
        |------------------------------------------------------------------
        | CHECK DUPLICATE
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM buses
            WHERE bus_number = ?
            AND id != ?
            LIMIT 1
        ");

        $check_stmt->bind_param(
            "si",
            $bus_number,
            $id
        );

        $check_stmt->execute();

        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {

            $error = "Another bus already uses this bus number.";

        }

        $check_stmt->close();


        /*
        |------------------------------------------------------------------
        | UPDATE
        |------------------------------------------------------------------
        */

        if ($error === "") {

            $update_stmt = $conn->prepare("
                UPDATE buses
                SET
                    bus_number = ?,
                    bus_name = ?,
                    total_seats = ?,
                    status = ?
                WHERE id = ?
            ");

            if (!$update_stmt) {

                $error = "Database error: " . $conn->error;

            } else {

                $seats = (int)$total_seats;

                $update_stmt->bind_param(
                    "ssisi",
                    $bus_number,
                    $bus_name,
                    $seats,
                    $status,
                    $id
                );

                if ($update_stmt->execute()) {

                    $message = "Bus updated successfully.";

                } else {

                    $error =
                        "Failed to update bus: "
                        . $update_stmt->error;

                }

                $update_stmt->close();

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| DELETE BUS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_bus"])) {

    $id = (int)($_POST["bus_id"] ?? 0);


    if ($id <= 0) {

        $error = "Invalid bus ID.";

    } else {

        /*
        |------------------------------------------------------------------
        | CHECK WHETHER BUS IS USED BY A SCHEDULE
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM schedules
            WHERE bus_id = ?
            LIMIT 1
        ");

        $check_stmt->bind_param(
            "i",
            $id
        );

        $check_stmt->execute();

        $check_result = $check_stmt->get_result();


        if ($check_result->num_rows > 0) {

            $error =
                "This bus cannot be deleted because it is already used in a schedule.";

        } else {

            $delete_stmt = $conn->prepare("
                DELETE FROM buses
                WHERE id = ?
            ");

            $delete_stmt->bind_param(
                "i",
                $id
            );


            if ($delete_stmt->execute()) {

                $message = "Bus deleted successfully.";

            } else {

                $error =
                    "Failed to delete bus: "
                    . $delete_stmt->error;

            }

            $delete_stmt->close();

        }

        $check_stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| EDIT BUTTON
|--------------------------------------------------------------------------
*/

if (isset($_GET["edit"])) {

    $edit_id = (int)$_GET["edit"];


    if ($edit_id > 0) {

        $edit_stmt = $conn->prepare("
            SELECT
                id,
                bus_number,
                bus_name,
                total_seats,
                status
            FROM buses
            WHERE id = ?
            LIMIT 1
        ");

        $edit_stmt->bind_param(
            "i",
            $edit_id
        );

        $edit_stmt->execute();

        $edit_result = $edit_stmt->get_result();


        if ($edit_result->num_rows === 1) {

            $edit_bus = $edit_result->fetch_assoc();

            $edit_mode = true;

            $edit_bus_id =
                $edit_bus["id"];

            $edit_bus_number =
                $edit_bus["bus_number"];

            $edit_bus_name =
                $edit_bus["bus_name"];

            $edit_total_seats =
                $edit_bus["total_seats"];

            $edit_status =
                $edit_bus["status"];

        }

        $edit_stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| GET BUSES
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        bus_number,
        bus_name,
        total_seats,
        status,
        created_at
    FROM buses
    ORDER BY id DESC
";

$result = $conn->query($sql);


if (!$result) {

    die(
        "Bus query error: "
        . $conn->error
    );

}


/*
|--------------------------------------------------------------------------
| COUNT BUSES
|--------------------------------------------------------------------------
*/

$count_sql = "
    SELECT COUNT(*) AS total
    FROM buses
";

$count_result =
    $conn->query($count_sql);

$total_buses = 0;


if ($count_result) {

    $count_row =
        $count_result->fetch_assoc();

    $total_buses =
        $count_row["total"];

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
        Buses | Admin | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        .bus-form {

            background: #ffffff;

            border: 1px solid #e7eaf0;

            border-radius: 14px;

            padding: 25px;

            margin-bottom: 25px;

        }


        .bus-form h2 {

            margin-bottom: 8px;

            color: #172033;

        }


        .bus-form p {

            color: #667085;

            margin-bottom: 22px;

        }


        .form-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;

        }


        .form-group {

            display: flex;

            flex-direction: column;

            gap: 7px;

        }


        .form-group label {

            font-size: 14px;

            font-weight: 600;

            color: #344054;

        }


        .form-group input,

        .form-group select {

            padding: 12px;

            border: 1px solid #d0d5dd;

            border-radius: 8px;

            font-size: 14px;

            outline: none;

        }


        .form-group input:focus,

        .form-group select:focus {

            border-color: #1769e0;

        }


        .form-buttons {

            margin-top: 20px;

            display: flex;

            gap: 10px;

        }


        .btn-primary {

            border: none;

            background: #1769e0;

            color: #ffffff;

            padding: 11px 18px;

            border-radius: 8px;

            cursor: pointer;

            font-weight: 600;

        }


        .btn-primary:hover {

            background: #1257bd;

        }


        .btn-secondary {

            background: #f2f4f7;

            color: #344054;

            padding: 11px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: 600;

        }


        .btn-edit {

            background: #edf4ff;

            color: #1769e0;

            border: none;

            padding: 7px 12px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

        }


        .btn-delete {

            background: #fef3f2;

            color: #b42318;

            border: none;

            padding: 7px 12px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 13px;

            font-weight: 600;

        }


        .message-success {

            background: #ecfdf3;

            color: #027a48;

            padding: 14px 18px;

            border-radius: 10px;

            margin-bottom: 20px;

        }


        .message-error {

            background: #fef3f2;

            color: #b42318;

            padding: 14px 18px;

            border-radius: 10px;

            margin-bottom: 20px;

        }


        @media (max-width: 700px) {

            .form-grid {

                grid-template-columns: 1fr;

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


            <a href="drivers.php">

                🧑‍✈️ Drivers

            </a>


            <a
                href="buses.php"
                class="active"
            >

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

                    Buses

                </h1>


                <p>

                    Manage UniBus vehicles

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 System Admin

            </div>


        </header>



        <!-- =====================================================
             MESSAGES
        ====================================================== -->

        <?php if ($message !== ""): ?>

            <div class="message-success">

                ✅ <?= htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="message-error">

                ❌ <?= htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>



        <!-- =====================================================
             ADD / EDIT FORM
        ====================================================== -->

        <?php if ($edit_mode): ?>


            <section class="bus-form">


                <h2>

                    ✏️ Edit Bus

                </h2>


                <p>

                    Update the bus information below.

                </p>


                <form
                    method="POST"
                    action="buses.php"
                >


                    <input
                        type="hidden"
                        name="bus_id"
                        value="<?= htmlspecialchars($edit_bus_id); ?>"
                    >


                    <div class="form-grid">


                        <div class="form-group">


                            <label>

                                Bus Number

                            </label>


                            <input
                                type="text"
                                name="bus_number"
                                value="<?= htmlspecialchars($edit_bus_number); ?>"
                                required
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Bus Name

                            </label>


                            <input
                                type="text"
                                name="bus_name"
                                value="<?= htmlspecialchars($edit_bus_name); ?>"
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Total Seats

                            </label>


                            <input
                                type="number"
                                name="total_seats"
                                value="<?= htmlspecialchars($edit_total_seats); ?>"
                                min="1"
                                required
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Status

                            </label>


                            <select name="status">


                                <option
                                    value="active"
                                    <?= $edit_status === "active" ? "selected" : ""; ?>
                                >

                                    Active

                                </option>


                                <option
                                    value="inactive"
                                    <?= $edit_status === "inactive" ? "selected" : ""; ?>
                                >

                                    Inactive

                                </option>


                            </select>


                        </div>


                    </div>


                    <div class="form-buttons">


                        <button
                            type="submit"
                            name="update_bus"
                            class="btn-primary"
                        >

                            💾 Update Bus

                        </button>


                        <a
                            href="buses.php"
                            class="btn-secondary"
                        >

                            Cancel

                        </a>


                    </div>


                </form>


            </section>


        <?php else: ?>


            <section class="bus-form">


                <h2>

                    ➕ Add New Bus

                </h2>


                <p>

                    Register a new UniBus vehicle.

                </p>


                <form
                    method="POST"
                    action="buses.php"
                >


                    <div class="form-grid">


                        <div class="form-group">


                            <label>

                                Bus Number

                            </label>


                            <input
                                type="text"
                                name="bus_number"
                                placeholder="Example: BUS-02"
                                required
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Bus Name

                            </label>


                            <input
                                type="text"
                                name="bus_name"
                                placeholder="Example: UniBus 02"
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Total Seats

                            </label>


                            <input
                                type="number"
                                name="total_seats"
                                placeholder="Example: 40"
                                min="1"
                                required
                            >


                        </div>


                        <div class="form-group">


                            <label>

                                Status

                            </label>


                            <select name="status">


                                <option value="active">

                                    Active

                                </option>


                                <option value="inactive">

                                    Inactive

                                </option>


                            </select>


                        </div>


                    </div>


                    <div class="form-buttons">


                        <button
                            type="submit"
                            name="add_bus"
                            class="btn-primary"
                        >

                            🚌 Add Bus

                        </button>


                    </div>


                </form>


            </section>


        <?php endif; ?>



        <!-- =====================================================
             STATISTICS
        ====================================================== -->

        <section class="admin-stats">


            <div class="admin-stat-card">


                <span class="stat-icon">

                    🚌

                </span>


                <div>


                    <p>

                        Total Buses

                    </p>


                    <h2>

                        <?= htmlspecialchars($total_buses); ?>

                    </h2>


                </div>


            </div>


        </section>



        <!-- =====================================================
             BUS LIST
        ====================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">


                <div>


                    <h2>

                        Bus List

                    </h2>


                    <p>

                        Registered UniBus vehicles

                    </p>


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

                                Bus Number

                            </th>


                            <th>

                                Bus Name

                            </th>


                            <th>

                                Capacity

                            </th>


                            <th>

                                Status

                            </th>


                            <th>

                                Created

                            </th>


                            <th>

                                Action

                            </th>


                        </tr>


                    </thead>



                    <tbody>


                    <?php if ($result->num_rows === 0): ?>


                        <tr>


                            <td
                                colspan="7"
                                class="no-data"
                            >


                                <div>

                                    🚌

                                </div>


                                <strong>

                                    No buses found

                                </strong>


                                <p>

                                    Add your first bus above.

                                </p>


                            </td>


                        </tr>


                    <?php else: ?>


                        <?php while ($bus = $result->fetch_assoc()): ?>


                            <tr>


                                <td>

                                    <?= htmlspecialchars(
                                        $bus["id"]
                                    ); ?>

                                </td>


                                <td>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $bus["bus_number"]
                                        ); ?>

                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $bus["bus_name"] ?? "-"
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $bus["total_seats"]
                                    ); ?>

                                    seats

                                </td>


                                <td>


                                    <?php if ($bus["status"] === "active"): ?>


                                        <span class="status confirmed">

                                            ● Active

                                        </span>


                                    <?php else: ?>


                                        <span class="status cancelled">

                                            ● Inactive

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $bus["created_at"]
                                            )
                                        )
                                    ); ?>

                                </td>


                                <td>


                                    <a
                                        href="buses.php?edit=<?= $bus["id"]; ?>"
                                        class="btn-edit"
                                    >

                                        ✏️ Edit

                                    </a>


                                    <form
                                        method="POST"
                                        action="buses.php"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this bus?');"
                                    >


                                        <input
                                            type="hidden"
                                            name="bus_id"
                                            value="<?= $bus["id"]; ?>"
                                        >


                                        <button
                                            type="submit"
                                            name="delete_bus"
                                            class="btn-delete"
                                        >

                                            🗑️ Delete

                                        </button>


                                    </form>


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