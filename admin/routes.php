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

$edit_route_id = "";
$edit_route_name = "";
$edit_start_point = "";
$edit_end_point = "";
$edit_stops = "";


/*
|--------------------------------------------------------------------------
| ADD ROUTE
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_route"])) {

    $route_name = trim($_POST["route_name"] ?? "");
    $start_point = trim($_POST["start_point"] ?? "");
    $end_point = trim($_POST["end_point"] ?? "");
    $stops = trim($_POST["stops"] ?? "");


    if ($route_name === "") {

        $error = "Route name is required.";

    } elseif ($start_point === "") {

        $error = "Start point is required.";

    } elseif ($end_point === "") {

        $error = "End point is required.";

    } else {


        /*
        |------------------------------------------------------------------
        | CHECK DUPLICATE ROUTE NAME
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM routes
            WHERE route_name = ?
            LIMIT 1
        ");

        $check_stmt->bind_param(
            "s",
            $route_name
        );

        $check_stmt->execute();

        $check_result = $check_stmt->get_result();


        if ($check_result->num_rows > 0) {

            $error = "This route name already exists.";

        }

        $check_stmt->close();


        /*
        |------------------------------------------------------------------
        | INSERT ROUTE
        |------------------------------------------------------------------
        */

        if ($error === "") {

            $insert_stmt = $conn->prepare("
                INSERT INTO routes
                (
                    route_name,
                    start_point,
                    end_point,
                    stops
                )
                VALUES (?, ?, ?, ?)
            ");


            if (!$insert_stmt) {

                $error =
                    "Database error: "
                    . $conn->error;

            } else {


                $insert_stmt->bind_param(
                    "ssss",
                    $route_name,
                    $start_point,
                    $end_point,
                    $stops
                );


                if ($insert_stmt->execute()) {

                    $message =
                        "Route added successfully.";

                } else {

                    $error =
                        "Failed to add route: "
                        . $insert_stmt->error;

                }


                $insert_stmt->close();

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| UPDATE ROUTE
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_route"])) {

    $id = (int)($_POST["route_id"] ?? 0);

    $route_name = trim($_POST["route_name"] ?? "");
    $start_point = trim($_POST["start_point"] ?? "");
    $end_point = trim($_POST["end_point"] ?? "");
    $stops = trim($_POST["stops"] ?? "");


    if ($id <= 0) {

        $error = "Invalid route ID.";

    } elseif ($route_name === "") {

        $error = "Route name is required.";

    } elseif ($start_point === "") {

        $error = "Start point is required.";

    } elseif ($end_point === "") {

        $error = "End point is required.";

    } else {


        /*
        |------------------------------------------------------------------
        | CHECK DUPLICATE ROUTE
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM routes
            WHERE route_name = ?
            AND id != ?
            LIMIT 1
        ");


        $check_stmt->bind_param(
            "si",
            $route_name,
            $id
        );


        $check_stmt->execute();


        $check_result =
            $check_stmt->get_result();


        if ($check_result->num_rows > 0) {

            $error =
                "Another route already uses this route name.";

        }


        $check_stmt->close();


        /*
        |------------------------------------------------------------------
        | UPDATE ROUTE
        |------------------------------------------------------------------
        */

        if ($error === "") {


            $update_stmt = $conn->prepare("
                UPDATE routes
                SET
                    route_name = ?,
                    start_point = ?,
                    end_point = ?,
                    stops = ?
                WHERE id = ?
            ");


            if (!$update_stmt) {

                $error =
                    "Database error: "
                    . $conn->error;

            } else {


                $update_stmt->bind_param(
                    "ssssi",
                    $route_name,
                    $start_point,
                    $end_point,
                    $stops,
                    $id
                );


                if ($update_stmt->execute()) {

                    $message =
                        "Route updated successfully.";

                } else {

                    $error =
                        "Failed to update route: "
                        . $update_stmt->error;

                }


                $update_stmt->close();

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| DELETE ROUTE
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_route"])) {

    $id = (int)($_POST["route_id"] ?? 0);


    if ($id <= 0) {

        $error = "Invalid route ID.";

    } else {


        /*
        |------------------------------------------------------------------
        | CHECK WHETHER ROUTE IS USED BY A SCHEDULE
        |------------------------------------------------------------------
        */

        $check_stmt = $conn->prepare("
            SELECT id
            FROM schedules
            WHERE route_id = ?
            LIMIT 1
        ");


        $check_stmt->bind_param(
            "i",
            $id
        );


        $check_stmt->execute();


        $check_result =
            $check_stmt->get_result();


        if ($check_result->num_rows > 0) {

            $error =
                "This route cannot be deleted because it is already used in a schedule.";

        } else {


            /*
            |--------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------
            */

            $delete_stmt = $conn->prepare("
                DELETE FROM routes
                WHERE id = ?
            ");


            $delete_stmt->bind_param(
                "i",
                $id
            );


            if ($delete_stmt->execute()) {

                $message =
                    "Route deleted successfully.";

            } else {

                $error =
                    "Failed to delete route: "
                    . $delete_stmt->error;

            }


            $delete_stmt->close();

        }


        $check_stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| EDIT ROUTE
|--------------------------------------------------------------------------
*/

if (isset($_GET["edit"])) {

    $edit_id = (int)$_GET["edit"];


    if ($edit_id > 0) {


        $edit_stmt = $conn->prepare("
            SELECT
                id,
                route_name,
                start_point,
                end_point,
                stops
            FROM routes
            WHERE id = ?
            LIMIT 1
        ");


        $edit_stmt->bind_param(
            "i",
            $edit_id
        );


        $edit_stmt->execute();


        $edit_result =
            $edit_stmt->get_result();


        if ($edit_result->num_rows === 1) {


            $edit_route =
                $edit_result->fetch_assoc();


            $edit_mode = true;


            $edit_route_id =
                $edit_route["id"];


            $edit_route_name =
                $edit_route["route_name"];


            $edit_start_point =
                $edit_route["start_point"];


            $edit_end_point =
                $edit_route["end_point"];


            $edit_stops =
                $edit_route["stops"];

        }


        $edit_stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| GET ROUTES
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        route_name,
        start_point,
        end_point,
        stops,
        created_at
    FROM routes
    ORDER BY id DESC
";


$result =
    $conn->query($sql);


if (!$result) {

    die(
        "Route query error: "
        . $conn->error
    );

}


/*
|--------------------------------------------------------------------------
| COUNT ROUTES
|--------------------------------------------------------------------------
*/

$count_sql = "
    SELECT COUNT(*) AS total
    FROM routes
";


$count_result =
    $conn->query($count_sql);


$total_routes = 0;


if ($count_result) {

    $count_row =
        $count_result->fetch_assoc();


    $total_routes =
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
        Routes | Admin | UniBus
    </title>


    <link
        rel="stylesheet"
        href="../css/style.css"
    >


    <style>

        .route-form {

            background: #ffffff;

            border: 1px solid #e7eaf0;

            border-radius: 14px;

            padding: 25px;

            margin-bottom: 25px;

        }


        .route-form h2 {

            margin-bottom: 8px;

            color: #172033;

        }


        .route-form p {

            color: #667085;

            margin-bottom: 22px;

        }


        .route-form-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;

        }


        .route-form-group {

            display: flex;

            flex-direction: column;

            gap: 7px;

        }


        .route-form-group.full {

            grid-column: 1 / -1;

        }


        .route-form-group label {

            font-size: 14px;

            font-weight: 600;

            color: #344054;

        }


        .route-form-group input,

        .route-form-group textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #d0d5dd;

            border-radius: 8px;

            font-size: 14px;

            outline: none;

            font-family: Arial, Helvetica, sans-serif;

        }


        .route-form-group textarea {

            min-height: 90px;

            resize: vertical;

        }


        .route-form-group input:focus,

        .route-form-group textarea:focus {

            border-color: #1769e0;

        }


        .route-buttons {

            display: flex;

            gap: 10px;

            margin-top: 20px;

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

            .route-form-grid {

                grid-template-columns: 1fr;

            }


            .route-form-group.full {

                grid-column: auto;

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


            <a href="buses.php">

                🚌 Buses

            </a>


            <a
                href="routes.php"
                class="active"
            >

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

                    Routes

                </h1>


                <p>

                    Manage UniBus routes

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
             ADD / EDIT ROUTE FORM
        ====================================================== -->

        <?php if ($edit_mode): ?>


            <section class="route-form">


                <h2>

                    ✏️ Edit Route

                </h2>


                <p>

                    Update the route information below.

                </p>


                <form
                    method="POST"
                    action="routes.php"
                >


                    <input
                        type="hidden"
                        name="route_id"
                        value="<?= htmlspecialchars($edit_route_id); ?>"
                    >


                    <div class="route-form-grid">


                        <div class="route-form-group">


                            <label>

                                Route Name

                            </label>


                            <input
                                type="text"
                                name="route_name"
                                value="<?= htmlspecialchars($edit_route_name); ?>"
                                required
                            >


                        </div>


                        <div class="route-form-group">


                            <label>

                                Start Point

                            </label>


                            <input
                                type="text"
                                name="start_point"
                                value="<?= htmlspecialchars($edit_start_point); ?>"
                                required
                            >


                        </div>


                        <div class="route-form-group">


                            <label>

                                End Point

                            </label>


                            <input
                                type="text"
                                name="end_point"
                                value="<?= htmlspecialchars($edit_end_point); ?>"
                                required
                            >


                        </div>


                        <div class="route-form-group full">


                            <label>

                                Stops

                            </label>


                            <textarea
                                name="stops"
                                placeholder="Example: Amberkhana, Zindabazar, Tilagor"
                            ><?= htmlspecialchars($edit_stops ?? ""); ?></textarea>


                        </div>


                    </div>


                    <div class="route-buttons">


                        <button
                            type="submit"
                            name="update_route"
                            class="btn-primary"
                        >

                            💾 Update Route

                        </button>


                        <a
                            href="routes.php"
                            class="btn-secondary"
                        >

                            Cancel

                        </a>


                    </div>


                </form>


            </section>


        <?php else: ?>


            <section class="route-form">


                <h2>

                    ➕ Add New Route

                </h2>


                <p>

                    Register a new UniBus route.

                </p>


                <form
                    method="POST"
                    action="routes.php"
                >


                    <div class="route-form-grid">


                        <div class="route-form-group">


                            <label>

                                Route Name

                            </label>


                            <input
                                type="text"
                                name="route_name"
                                placeholder="Example: City - SEC Campus"
                                required
                            >


                        </div>


                        <div class="route-form-group">


                            <label>

                                Start Point

                            </label>


                            <input
                                type="text"
                                name="start_point"
                                placeholder="Example: City"
                                required
                            >


                        </div>


                        <div class="route-form-group">


                            <label>

                                End Point

                            </label>


                            <input
                                type="text"
                                name="end_point"
                                placeholder="Example: SEC Campus"
                                required
                            >


                        </div>


                        <div class="route-form-group full">


                            <label>

                                Stops

                            </label>


                            <textarea
                                name="stops"
                                placeholder="Example: Amberkhana, Zindabazar, Tilagor"
                            ></textarea>


                        </div>


                    </div>


                    <div class="route-buttons">


                        <button
                            type="submit"
                            name="add_route"
                            class="btn-primary"
                        >

                            🛣️ Add Route

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

                    🛣️

                </span>


                <div>


                    <p>

                        Total Routes

                    </p>


                    <h2>

                        <?= htmlspecialchars($total_routes); ?>

                    </h2>


                </div>


            </div>


        </section>



        <!-- =====================================================
             ROUTE LIST
        ====================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">


                <div>


                    <h2>

                        Route List

                    </h2>


                    <p>

                        Registered bus routes

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

                                Route Name

                            </th>


                            <th>

                                Start Point

                            </th>


                            <th>

                                End Point

                            </th>


                            <th>

                                Stops

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

                                    🛣️

                                </div>


                                <strong>

                                    No routes found

                                </strong>


                                <p>

                                    Add your first route above.

                                </p>


                            </td>


                        </tr>


                    <?php else: ?>


                        <?php while ($route = $result->fetch_assoc()): ?>


                            <tr>


                                <td>

                                    <?= htmlspecialchars(
                                        $route["id"]
                                    ); ?>

                                </td>


                                <td>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $route["route_name"]
                                        ); ?>

                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $route["start_point"]
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $route["end_point"]
                                    ); ?>

                                </td>


                                <td>

                                    <?php if (!empty($route["stops"])): ?>

                                        <?= htmlspecialchars(
                                            $route["stops"]
                                        ); ?>

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $route["created_at"]
                                            )
                                        )
                                    ); ?>

                                </td>


                                <td>


                                    <a
                                        href="routes.php?edit=<?= $route["id"]; ?>"
                                        class="btn-edit"
                                    >

                                        ✏️ Edit

                                    </a>


                                    <form
                                        method="POST"
                                        action="routes.php"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this route?');"
                                    >


                                        <input
                                            type="hidden"
                                            name="route_id"
                                            value="<?= $route["id"]; ?>"
                                        >


                                        <button
                                            type="submit"
                                            name="delete_route"
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