<?php

session_start();

require_once "../config/database.php";

/*
====================================================
ADMIN SESSION CHECK
====================================================
*/

if (!isset($_SESSION["user_id"])) {

    header("Location: admin-login.php");
    exit;

}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {

    session_unset();
    session_destroy();

    header("Location: admin-login.php");
    exit;

}


/*
====================================================
ADMIN INFORMATION
====================================================
*/

$admin_name = $_SESSION["name"] ?? "Admin";
$admin_email = $_SESSION["email"] ?? "";


/*
====================================================
DASHBOARD STATISTICS
====================================================
*/


/* Total Students */

$total_students = 0;

$sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'student'";

$result = $conn->query($sql);

if ($result) {

    $row = $result->fetch_assoc();

    $total_students = $row["total"];

}


/* Total Drivers */

$total_drivers = 0;

$sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'driver'";

$result = $conn->query($sql);

if ($result) {

    $row = $result->fetch_assoc();

    $total_drivers = $row["total"];

}


/* Total Buses */

$total_buses = 0;

$sql = "SELECT COUNT(*) AS total
        FROM buses";

$result = $conn->query($sql);

if ($result) {

    $row = $result->fetch_assoc();

    $total_buses = $row["total"];

}


/* Total Bookings */

$total_bookings = 0;

$sql = "SELECT COUNT(*) AS total
        FROM bookings";

$result = $conn->query($sql);

if ($result) {

    $row = $result->fetch_assoc();

    $total_bookings = $row["total"];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Admin Dashboard | UniBus
    </title>

    <link rel="stylesheet"
          href="../css/style.css">

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


            <a href="admin-dashboard.php"
               class="active">

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


        <a href="admin-logout.php"
           class="admin-logout">

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

                    Admin Dashboard

                </h1>


                <p>

                    Welcome back,
                    <?php
                    echo htmlspecialchars($admin_name);
                    ?>!

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼

                <?php
                echo htmlspecialchars($admin_name);
                ?>

            </div>


        </header>



        <!-- =================================================
             STAT CARDS
        ================================================== -->

        <section class="admin-stats">


            <!-- STUDENTS -->

            <div class="admin-stat-card">


                <span class="stat-icon">

                    👨‍🎓

                </span>


                <div>

                    <p>

                        Total Students

                    </p>


                    <h2>

                        <?php
                        echo $total_students;
                        ?>

                    </h2>

                </div>


            </div>



            <!-- BUSES -->

            <div class="admin-stat-card">


                <span class="stat-icon">

                    🚌

                </span>


                <div>

                    <p>

                        Total Buses

                    </p>


                    <h2>

                        <?php
                        echo $total_buses;
                        ?>

                    </h2>

                </div>


            </div>



            <!-- BOOKINGS -->

            <div class="admin-stat-card">


                <span class="stat-icon">

                    🎫

                </span>


                <div>

                    <p>

                        Total Bookings

                    </p>


                    <h2>

                        <?php
                        echo $total_bookings;
                        ?>

                    </h2>

                </div>


            </div>



            <!-- DRIVERS -->

            <div class="admin-stat-card">


                <span class="stat-icon">

                    🧑‍✈️

                </span>


                <div>

                    <p>

                        Total Drivers

                    </p>


                    <h2>

                        <?php
                        echo $total_drivers;
                        ?>

                    </h2>

                </div>


            </div>


        </section>



        <!-- =================================================
             QUICK ACTIONS
        ================================================== -->

        <section class="admin-section">


            <div class="section-heading">


                <div>

                    <h2>

                        Quick Actions

                    </h2>


                    <p>

                        Manage the UniBus system

                    </p>

                </div>


            </div>



            <div class="admin-actions">


                <a href="students.php"
                   class="admin-action">

                    👨‍🎓

                    <span>

                        Manage Students

                    </span>

                </a>


                <a href="drivers.php"
                   class="admin-action">

                    🧑‍✈️

                    <span>

                        Manage Drivers

                    </span>

                </a>


                <a href="buses.php"
                   class="admin-action">

                    🚌

                    <span>

                        Manage Buses

                    </span>

                </a>


                <a href="routes.php"
                   class="admin-action">

                    🛣️

                    <span>

                        Manage Routes

                    </span>

                </a>


                <a href="schedules.php"
                   class="admin-action">

                    🕐

                    <span>

                        Manage Schedules

                    </span>

                </a>


                <a href="notices.php"
                   class="admin-action">

                    📢

                    <span>

                        Post Notice

                    </span>

                </a>


            </div>


        </section>



        <!-- =================================================
             RECENT BOOKINGS
        ================================================== -->

        <section class="admin-section">


            <div class="section-heading">


                <div>

                    <h2>

                        Recent Bookings

                    </h2>


                    <p>

                        Latest student reservations

                    </p>

                </div>


                <a href="bookings.php">

                    View All

                </a>


            </div>


            <?php

            $recent_sql = "

                SELECT

                    bookings.id,

                    users.name,

                    users.student_id,

                    bookings.seat_number,

                    bookings.status,

                    schedules.travel_date,

                    buses.bus_number

                FROM bookings

                INNER JOIN users

                    ON bookings.user_id = users.id

                INNER JOIN schedules

                    ON bookings.schedule_id = schedules.id

                INNER JOIN buses

                    ON schedules.bus_id = buses.id

                ORDER BY bookings.id DESC

                LIMIT 5

            ";


            $recent_result =
                $conn->query($recent_sql);

            ?>


            <?php if (
                $recent_result &&
                $recent_result->num_rows > 0
            ): ?>


                <div class="admin-table-container">


                    <table class="admin-table">


                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Student</th>

                                <th>Student ID</th>

                                <th>Bus</th>

                                <th>Seat</th>

                                <th>Date</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while (
                            $booking =
                            $recent_result->fetch_assoc()
                        ): ?>


                            <tr>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["id"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["name"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["student_id"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["bus_number"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["seat_number"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["travel_date"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $booking["status"]
                                        )
                                    );
                                    ?>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                        </tbody>


                    </table>


                </div>


            <?php else: ?>


                <div class="admin-empty">


                    <div>

                        🎫

                    </div>


                    <h3>

                        No bookings yet

                    </h3>


                    <p>

                        Booking records will appear here.

                    </p>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>

</body>

</html>