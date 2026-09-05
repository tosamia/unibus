<?php

session_start();

require_once "config/database.php";


/* =====================================================
   1. GET FILTER VALUES
===================================================== */

$selected_date = $_GET["date"] ?? date("Y-m-d", strtotime("+1 day"));
$selected_route = $_GET["route"] ?? "";


/* =====================================================
   2. GET ROUTES FOR FILTER
===================================================== */

$route_sql = "
    SELECT id, route_name
    FROM routes
    ORDER BY route_name ASC
";

$route_result = $conn->query($route_sql);

if (!$route_result) {
    die("Route query failed: " . $conn->error);
}


/* =====================================================
   3. GET BUS SCHEDULES
===================================================== */

$sql = "
    SELECT
        s.id AS schedule_id,
        s.departure_time,
        s.arrival_time,
        s.travel_date,
        s.status AS schedule_status,

        b.id AS bus_id,
        b.bus_number,
        b.bus_name,
        b.total_seats,
        b.status AS bus_status,

        r.id AS route_id,
        r.route_name,
        r.start_point,
        r.end_point

    FROM schedules s

    INNER JOIN buses b
        ON s.bus_id = b.id

    INNER JOIN routes r
        ON s.route_id = r.id

    WHERE s.travel_date = ?
      AND s.status = 'scheduled'
      AND b.status = 'active'
";


/* =====================================================
   4. ADD ROUTE FILTER
===================================================== */

if (!empty($selected_route)) {

    $sql .= " AND r.id = ?";

}


/* =====================================================
   5. ORDER
===================================================== */

$sql .= "
    ORDER BY s.departure_time ASC
";


$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Schedule query failed: " . $conn->error);
}


/* =====================================================
   6. BIND PARAMETERS
===================================================== */

if (!empty($selected_route)) {

    $stmt->bind_param(
        "si",
        $selected_date,
        $selected_route
    );

} else {

    $stmt->bind_param(
        "s",
        $selected_date
    );

}


/* =====================================================
   7. EXECUTE
===================================================== */

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bus Schedule | UniBus</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <link
        rel="stylesheet"
        href="css/schedule.css"
    >

</head>


<body>


<!-- =================================================
     NAVBAR
================================================= -->

<nav class="navbar">

    <div class="logo">
        🚌 UniBus
    </div>


    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="about.php">
            About
        </a>

        <a href="schedule.php" class="active">
            Bus Schedule
        </a>

        <a href="notices.php">
            Notices
        </a>

        <a href="contact.php">
            Contact
        </a>

    </div>


    <div class="nav-buttons">

        <?php if (isset($_SESSION["user_id"])): ?>

            <a
                href="student-dashboard.php"
                class="login-btn"
            >
                Dashboard
            </a>

            <a
                href="logout.php"
                class="register-btn"
            >
                Logout
            </a>

        <?php else: ?>

            <a
                href="login.php"
                class="login-btn"
            >
                Login
            </a>

            <a
                href="register.php"
                class="register-btn"
            >
                Register
            </a>

        <?php endif; ?>

    </div>

</nav>



<!-- =================================================
     PAGE HEADER
================================================= -->

<section class="schedule-header">

    <span class="section-label">
        BUS SCHEDULE
    </span>


    <h1>
        University Bus Schedule
    </h1>


    <p>
        Check bus routes, departure times, and arrival
        information before your journey.
    </p>

</section>



<!-- =================================================
     SCHEDULE SECTION
================================================= -->

<section class="schedule-section">

    <div class="schedule-container">


        <!-- =================================================
             FILTER
        ================================================= -->

        <form
            method="GET"
            action="schedule.php"
            class="schedule-filter"
        >


            <!-- DATE -->

            <div class="filter-group">

                <label for="date">
                    Select Date
                </label>

                <input
                    type="date"
                    id="date"
                    name="date"
                    value="<?= htmlspecialchars($selected_date); ?>"
                >

            </div>



            <!-- ROUTE -->

            <div class="filter-group">

                <label for="route">
                    Select Route
                </label>


                <select
                    id="route"
                    name="route"
                >

                    <option value="">
                        All Routes
                    </option>


                    <?php while ($route = $route_result->fetch_assoc()): ?>

                        <option
                            value="<?= $route["id"]; ?>"
                            <?= (
                                $selected_route == $route["id"]
                            ) ? "selected" : ""; ?>
                        >

                            <?= htmlspecialchars(
                                $route["route_name"]
                            ); ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>



            <!-- SEARCH -->

            <button
                class="filter-btn"
                type="submit"
            >
                Search Schedule
            </button>


        </form>



        <!-- =================================================
             SCHEDULE LIST
        ================================================= -->

        <div class="schedule-list">


            <?php if ($result->num_rows === 0): ?>


                <!-- NO BUS AVAILABLE -->

                <div class="no-bus">

                    <div class="no-bus-icon">
                        🚌
                    </div>


                    <h2>
                        No Bus Available
                    </h2>


                    <p>

                        There are no buses scheduled for

                        <strong>
                            <?= htmlspecialchars(
                                date(
                                    "M d, Y",
                                    strtotime($selected_date)
                                )
                            ); ?>
                        </strong>.

                    </p>


                    <a
                        href="schedule.php"
                        class="book-btn"
                    >
                        View Other Dates
                    </a>

                </div>


            <?php else: ?>


                <!-- =================================================
                     DATABASE SCHEDULES
                ================================================= -->


                <?php while ($schedule = $result->fetch_assoc()): ?>


                    <div class="schedule-card">


                        <!-- BUS INFORMATION -->

                        <div class="bus-info">


                            <div class="bus-icon">
                                🚌
                            </div>


                            <div>

                                <h3>

                                    <?= htmlspecialchars(
                                        $schedule["bus_number"]
                                    ); ?>

                                </h3>


                                <p>

                                    <?= htmlspecialchars(
                                        $schedule["route_name"]
                                    ); ?>

                                </p>

                            </div>

                        </div>



                        <!-- ROUTE / TIME -->

                        <div class="route-info">


                            <div>

                                <span>
                                    Departure
                                </span>


                                <strong>

                                    <?= date(
                                        "h:i A",
                                        strtotime(
                                            $schedule["departure_time"]
                                        )
                                    ); ?>

                                </strong>

                            </div>



                            <div class="route-arrow">
                                →
                            </div>



                            <div>

                                <span>
                                    Arrival
                                </span>


                                <strong>

                                    <?= date(
                                        "h:i A",
                                        strtotime(
                                            $schedule["arrival_time"]
                                        )
                                    ); ?>

                                </strong>

                            </div>

                        </div>



                        <!-- ACTION -->

                        <div class="schedule-action">


                            <span class="available">
                                ● Available
                            </span>


                            <?php if (isset($_SESSION["user_id"])): ?>


                                <a
                                    href="seat-booking.php?schedule_id=<?= $schedule["schedule_id"]; ?>"
                                    class="book-btn"
                                >
                                    Book Seat
                                </a>


                            <?php else: ?>


                                <a
                                    href="login.php"
                                    class="book-btn"
                                >
                                    Login to Book
                                </a>


                            <?php endif; ?>


                        </div>


                    </div>


                <?php endwhile; ?>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- =================================================
     FOOTER
================================================= -->

<footer class="footer">

    <div class="footer-content">


        <div>

            <h3>
                🚌 UniBus
            </h3>

            <p>
                University Bus Booking and Management System
            </p>

        </div>



        <div>

            <h3>
                Quick Links
            </h3>


            <a href="index.php">
                Home
            </a>


            <a href="about.php">
                About
            </a>


            <a href="schedule.php">
                Bus Schedule
            </a>


            <a href="notices.php">
                Notices
            </a>

        </div>



        <div>

            <h3>
                Contact Info
            </h3>


            <p>
                📞 +880 1712-345678
            </p>


            <p>
                ✉ info@unibus.sec.edu.bd
            </p>


            <p>
                📍 Sylhet Engineering College
            </p>

        </div>

    </div>



    <div class="footer-bottom">

        © 2026 UniBus. All rights reserved.

    </div>

</footer>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>