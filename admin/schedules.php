<?php

session_start();

require_once "../config/database.php";


/* =====================================================
   ADMIN LOGIN CHECK
===================================================== */

if (
    !isset($_SESSION["user_id"]) ||
    ($_SESSION["role"] ?? "") !== "admin"
) {
    header("Location: admin-login.php");
    exit;
}


/* =====================================================
   DELETE SCHEDULE
===================================================== */

if (isset($_GET["delete"])) {

    $delete_id = (int) $_GET["delete"];

    if ($delete_id > 0) {

        $delete_sql = "DELETE FROM schedules WHERE id = ?";

        $delete_stmt = $conn->prepare($delete_sql);

        if ($delete_stmt) {

            $delete_stmt->bind_param(
                "i",
                $delete_id
            );

            $delete_stmt->execute();

            $delete_stmt->close();
        }
    }

    header("Location: schedules.php");
    exit;
}


/* =====================================================
   ADD SCHEDULE
===================================================== */

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $bus_id = (int) ($_POST["bus_id"] ?? 0);

    $route_id = (int) ($_POST["route_id"] ?? 0);

    $driver_id = (int) ($_POST["driver_id"] ?? 0);

    $departure_time = trim(
        $_POST["departure_time"] ?? ""
    );

    $arrival_time = trim(
        $_POST["arrival_time"] ?? ""
    );

    $travel_date = trim(
        $_POST["travel_date"] ?? ""
    );

    $status = $_POST["status"] ?? "scheduled";


    /* =================================================
       VALIDATION
    ================================================= */

    if (
        $bus_id <= 0 ||
        $route_id <= 0 ||
        $driver_id <= 0 ||
        $departure_time === "" ||
        $arrival_time === "" ||
        $travel_date === ""
    ) {

        $message =
            "Please fill in all schedule fields.";

        $message_type = "error";

    } else {


        /* =============================================
           INSERT SCHEDULE
        ============================================= */

        $insert_sql = "
            INSERT INTO schedules
            (
                bus_id,
                route_id,
                driver_id,
                departure_time,
                arrival_time,
                travel_date,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $insert_stmt =
            $conn->prepare($insert_sql);


        if (!$insert_stmt) {

            $message =
                "Schedule error: " . $conn->error;

            $message_type = "error";

        } else {

            $insert_stmt->bind_param(
                "iiissss",
                $bus_id,
                $route_id,
                $driver_id,
                $departure_time,
                $arrival_time,
                $travel_date,
                $status
            );


            if ($insert_stmt->execute()) {

                $message =
                    "Schedule added successfully.";

                $message_type = "success";

            } else {

                $message =
                    "Failed to add schedule: "
                    . $insert_stmt->error;

                $message_type = "error";
            }


            $insert_stmt->close();
        }
    }
}


/* =====================================================
   GET ACTIVE BUSES
===================================================== */

$buses_sql = "
    SELECT
        id,
        bus_number,
        bus_name
    FROM buses
    WHERE status = 'active'
    ORDER BY bus_number
";

$buses_result =
    $conn->query($buses_sql);


if (!$buses_result) {

    die(
        "Bus query error: "
        . $conn->error
    );
}


/* =====================================================
   GET ROUTES
===================================================== */

$routes_sql = "
    SELECT
        id,
        route_name,
        start_point,
        end_point
    FROM routes
    ORDER BY route_name
";

$routes_result =
    $conn->query($routes_sql);


if (!$routes_result) {

    die(
        "Route query error: "
        . $conn->error
    );
}


/* =====================================================
   GET DRIVERS
===================================================== */

$drivers_sql = "
    SELECT
        id,
        name,
        email
    FROM users
    WHERE role = 'driver'
    ORDER BY name
";

$drivers_result =
    $conn->query($drivers_sql);


if (!$drivers_result) {

    die(
        "Driver query error: "
        . $conn->error
    );
}


/* =====================================================
   GET ALL SCHEDULES
===================================================== */

$schedules_sql = "
    SELECT

        schedules.id,

        schedules.departure_time,

        schedules.arrival_time,

        schedules.travel_date,

        schedules.status,

        buses.bus_number,

        buses.bus_name,

        routes.route_name,

        routes.start_point,

        routes.end_point,

        users.name AS driver_name

    FROM schedules

    INNER JOIN buses
        ON schedules.bus_id = buses.id

    INNER JOIN routes
        ON schedules.route_id = routes.id

    LEFT JOIN users
        ON schedules.driver_id = users.id

    ORDER BY
        schedules.travel_date DESC,
        schedules.departure_time ASC
";

$schedules_result =
    $conn->query($schedules_sql);


if (!$schedules_result) {

    die(
        "Schedule query error: "
        . $conn->error
    );
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
        Schedules | Admin | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >


    <style>

        .form-box {

            background: #ffffff;

            padding: 25px;

            border-radius: 12px;

            margin-bottom: 25px;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.06);
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

            font-weight: 600;
        }


        .form-group input,
        .form-group select {

            padding: 11px;

            border: 1px solid #ddd;

            border-radius: 7px;

            font-size: 15px;
        }


        .add-schedule-btn {

            margin-top: 20px;

            padding: 12px 22px;

            border: none;

            border-radius: 7px;

            cursor: pointer;

            font-weight: 600;
        }


        .success {

            background: #e8f7ed;

            color: #187a36;

            padding: 14px;

            border-radius: 8px;

            margin-bottom: 20px;
        }


        .error {

            background: #fdeaea;

            color: #b42318;

            padding: 14px;

            border-radius: 8px;

            margin-bottom: 20px;
        }


        .status {

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: 600;
        }


        .status-scheduled {

            background: #e8f1ff;

            color: #185abc;
        }


        .status-completed {

            background: #e8f7ed;

            color: #187a36;
        }


        .status-cancelled {

            background: #fdeaea;

            color: #b42318;
        }


        .delete-btn {

            color: #b42318;

            text-decoration: none;

            font-weight: 600;
        }


        .route-small {

            font-size: 13px;

            color: #777;

            margin-top: 4px;
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


    <!-- =================================================
         SIDEBAR
    ================================================== -->

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


            <a href="routes.php">

                🛣️ Routes

            </a>


            <a
                href="schedules.php"
                class="active"
            >

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



    <!-- =================================================
         MAIN CONTENT
    ================================================== -->

    <main class="admin-main">


        <!-- HEADER -->

        <header class="admin-header">


            <div>

                <h1>

                    Schedules

                </h1>


                <p>

                    Manage bus schedules and trips

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 System Admin

            </div>


        </header>



        <!-- =================================================
             MESSAGE
        ================================================== -->

        <?php if ($message !== ""): ?>

            <div
                class="<?php echo $message_type; ?>"
            >

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             ADD SCHEDULE
        ================================================== -->

        <section class="admin-section">


            <div class="section-heading">


                <h2>

                    Add New Schedule

                </h2>


                <p>

                    Assign a bus, route and driver
                    to a trip.

                </p>


            </div>



            <div class="form-box">


                <form
                    method="POST"
                    action="schedules.php"
                >


                    <div class="form-grid">


                        <!-- BUS -->

                        <div class="form-group">


                            <label for="bus_id">

                                Select Bus

                            </label>


                            <select
                                name="bus_id"
                                id="bus_id"
                                required
                            >


                                <option value="">

                                    -- Select Bus --

                                </option>


                                <?php

                                while (
                                    $bus =
                                    $buses_result->fetch_assoc()
                                ):

                                ?>


                                    <option
                                        value="<?php
                                        echo $bus["id"];
                                        ?>"
                                    >


                                        <?php

                                        echo htmlspecialchars(
                                            $bus["bus_number"]
                                        );

                                        ?>


                                        <?php

                                        if (
                                            !empty(
                                                $bus["bus_name"]
                                            )
                                        ):

                                        ?>

                                            -

                                            <?php

                                            echo htmlspecialchars(
                                                $bus["bus_name"]
                                            );

                                            ?>

                                        <?php endif; ?>


                                    </option>


                                <?php endwhile; ?>


                            </select>


                        </div>



                        <!-- ROUTE -->

                        <div class="form-group">


                            <label for="route_id">

                                Select Route

                            </label>


                            <select
                                name="route_id"
                                id="route_id"
                                required
                            >


                                <option value="">

                                    -- Select Route --

                                </option>


                                <?php

                                while (
                                    $route =
                                    $routes_result->fetch_assoc()
                                ):

                                ?>


                                    <option
                                        value="<?php
                                        echo $route["id"];
                                        ?>"
                                    >


                                        <?php

                                        echo htmlspecialchars(
                                            $route["route_name"]
                                        );

                                        ?>

                                        -

                                        <?php

                                        echo htmlspecialchars(
                                            $route["start_point"]
                                        );

                                        ?>

                                        →

                                        <?php

                                        echo htmlspecialchars(
                                            $route["end_point"]
                                        );

                                        ?>


                                    </option>


                                <?php endwhile; ?>


                            </select>


                        </div>



                        <!-- DRIVER -->

                        <div class="form-group">


                            <label for="driver_id">

                                Select Driver

                            </label>


                            <select
                                name="driver_id"
                                id="driver_id"
                                required
                            >


                                <option value="">

                                    -- Select Driver --

                                </option>


                                <?php

                                while (
                                    $driver =
                                    $drivers_result->fetch_assoc()
                                ):

                                ?>


                                    <option
                                        value="<?php
                                        echo $driver["id"];
                                        ?>"
                                    >


                                        <?php

                                        echo htmlspecialchars(
                                            $driver["name"]
                                        );

                                        ?>


                                        -

                                        <?php

                                        echo htmlspecialchars(
                                            $driver["email"]
                                        );

                                        ?>


                                    </option>


                                <?php endwhile; ?>


                            </select>


                        </div>



                        <!-- DEPARTURE -->

                        <div class="form-group">


                            <label for="departure_time">

                                Departure Time

                            </label>


                            <input
                                type="time"
                                name="departure_time"
                                id="departure_time"
                                required
                            >


                        </div>



                        <!-- ARRIVAL -->

                        <div class="form-group">


                            <label for="arrival_time">

                                Arrival Time

                            </label>


                            <input
                                type="time"
                                name="arrival_time"
                                id="arrival_time"
                                required
                            >


                        </div>



                        <!-- DATE -->

                        <div class="form-group">


                            <label for="travel_date">

                                Travel Date

                            </label>


                            <input
                                type="date"
                                name="travel_date"
                                id="travel_date"
                                required
                            >


                        </div>



                        <!-- STATUS -->

                        <div class="form-group">


                            <label for="status">

                                Status

                            </label>


                            <select
                                name="status"
                                id="status"
                            >


                                <option
                                    value="scheduled"
                                >

                                    Scheduled

                                </option>


                                <option
                                    value="completed"
                                >

                                    Completed

                                </option>


                                <option
                                    value="cancelled"
                                >

                                    Cancelled

                                </option>


                            </select>


                        </div>


                    </div>



                    <button
                        type="submit"
                        class="add-schedule-btn"
                    >

                        + Add Schedule

                    </button>


                </form>


            </div>


        </section>



        <!-- =================================================
             SCHEDULE LIST
        ================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">


                <div>


                    <h2>

                        Schedule List

                    </h2>


                    <p>

                        View and manage scheduled
                        bus trips

                    </p>


                </div>


            </div>



            <div class="admin-table-container">


                <table class="admin-table">


                    <thead>


                        <tr>

                            <th>ID</th>

                            <th>Route</th>

                            <th>Bus</th>

                            <th>Driver</th>

                            <th>Departure</th>

                            <th>Arrival</th>

                            <th>Trip Date</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>


                    </thead>



                    <tbody>


                    <?php

                    if (
                        $schedules_result->num_rows === 0
                    ):

                    ?>


                        <tr>


                            <td
                                colspan="9"
                                class="no-data"
                            >


                                <div>

                                    🕐

                                </div>


                                <strong>

                                    No schedules found

                                </strong>


                                <p>

                                    Add your first bus
                                    schedule above.

                                </p>


                            </td>


                        </tr>


                    <?php else: ?>


                        <?php

                        while (
                            $schedule =
                            $schedules_result->fetch_assoc()
                        ):

                        ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $schedule["id"]
                                    );

                                    ?>

                                </td>



                                <!-- ROUTE -->

                                <td>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $schedule["route_name"]
                                        );

                                        ?>

                                    </strong>


                                    <div class="route-small">

                                        <?php

                                        echo htmlspecialchars(
                                            $schedule["start_point"]
                                        );

                                        ?>

                                        →

                                        <?php

                                        echo htmlspecialchars(
                                            $schedule["end_point"]
                                        );

                                        ?>

                                    </div>


                                </td>



                                <!-- BUS -->

                                <td>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $schedule["bus_number"]
                                        );

                                        ?>

                                    </strong>


                                    <?php

                                    if (
                                        !empty(
                                            $schedule["bus_name"]
                                        )
                                    ):

                                    ?>


                                        <div class="route-small">

                                            <?php

                                            echo htmlspecialchars(
                                                $schedule["bus_name"]
                                            );

                                            ?>

                                        </div>


                                    <?php endif; ?>


                                </td>



                                <!-- DRIVER -->

                                <td>


                                    <?php

                                    if (
                                        !empty(
                                            $schedule["driver_name"]
                                        )
                                    ) {

                                        echo htmlspecialchars(
                                            $schedule["driver_name"]
                                        );

                                    } else {

                                        echo "Not assigned";

                                    }

                                    ?>


                                </td>



                                <!-- DEPARTURE -->

                                <td>


                                    <?php

                                    echo htmlspecialchars(
                                        date(
                                            "h:i A",
                                            strtotime(
                                                $schedule[
                                                    "departure_time"
                                                ]
                                            )
                                        )
                                    );

                                    ?>


                                </td>



                                <!-- ARRIVAL -->

                                <td>


                                    <?php

                                    echo htmlspecialchars(
                                        date(
                                            "h:i A",
                                            strtotime(
                                                $schedule[
                                                    "arrival_time"
                                                ]
                                            )
                                        )
                                    );

                                    ?>


                                </td>



                                <!-- DATE -->

                                <td>


                                    <?php

                                    echo htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $schedule[
                                                    "travel_date"
                                                ]
                                            )
                                        )
                                    );

                                    ?>


                                </td>



                                <!-- STATUS -->

                                <td>


                                    <?php

                                    $status =
                                        strtolower(
                                            $schedule["status"]
                                        );

                                    $status_class =
                                        "status-" . $status;

                                    ?>


                                    <span
                                        class="status
                                        <?php
                                        echo $status_class;
                                        ?>"
                                    >


                                        <?php

                                        echo htmlspecialchars(
                                            ucfirst($status)
                                        );

                                        ?>


                                    </span>


                                </td>



                                <!-- ACTION -->

                                <td>


                                    <a
                                        href="schedules.php?delete=<?php
                                        echo $schedule["id"];
                                        ?>"
                                        class="delete-btn"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this schedule?'
                                        );"
                                    >

                                        Delete

                                    </a>


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