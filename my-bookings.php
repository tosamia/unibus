<?php

session_start();

require_once "config/database.php";


/* =====================================================
   1. CHECK LOGIN
===================================================== */

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}

$user_id = $_SESSION["user_id"];


/* =====================================================
   2. GET CURRENT USER BOOKINGS
===================================================== */

$sql = "SELECT
            bookings.id AS booking_id,
            bookings.seat_number,
            bookings.booking_date,
            bookings.status,

            schedules.departure_time,
            schedules.arrival_time,
            schedules.travel_date,

            buses.bus_number,
            buses.bus_name,

            routes.route_name,
            routes.start_point,
            routes.end_point

        FROM bookings

        INNER JOIN schedules
            ON bookings.schedule_id = schedules.id

        INNER JOIN buses
            ON schedules.bus_id = buses.id

        INNER JOIN routes
            ON schedules.route_id = routes.id

        WHERE bookings.user_id = ?

        ORDER BY bookings.booking_date DESC";


$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Booking query error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Bookings | UniBus</title>

    <link rel="stylesheet"
          href="css/style.css">

    <link rel="stylesheet"
          href="css/my-bookings.css">

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

        <a href="schedule.php">
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

        <a href="student-dashboard.php"
           class="login-btn">

            Dashboard

        </a>


        <a href="logout.php"
           class="register-btn">

            Logout

        </a>

    </div>

</nav>


<!-- =================================================
     HEADER
================================================= -->

<section class="bookings-header">

    <span class="section-label">
        MY BOOKINGS
    </span>


    <h1>
        My Bus Bookings
    </h1>


    <p>
        View and manage your bus reservations.
    </p>

</section>


<!-- =================================================
     BOOKINGS
================================================= -->

<section class="bookings-section">

    <div class="bookings-container">


        <!-- PAGE TOP -->

        <div class="bookings-top">

            <div>

                <h2>
                    Booking History
                </h2>

                <p>
                    Your recent bus reservations
                </p>

            </div>


            <a href="schedule.php"
               class="new-booking-btn">

                + New Booking

            </a>

        </div>


        <!-- =================================================
             DATABASE BOOKINGS
        ================================================= -->

        <?php if ($result->num_rows === 0): ?>


            <!-- NO BOOKINGS -->

            <div class="booking-note">

                <strong>
                    📋 No Bookings Yet
                </strong>

                <p>
                    You have not made any bus reservations yet.
                </p>

                <a href="schedule.php"
                   class="new-booking-btn">

                    Book a Bus

                </a>

            </div>


        <?php else: ?>


            <?php while ($booking = $result->fetch_assoc()): ?>


                <!-- BOOKING CARD -->

                <div class="booking-card">


                    <!-- MAIN INFORMATION -->

                    <div class="booking-main">


                        <div class="booking-icon">
                            🚌
                        </div>


                        <div class="booking-info">


                            <div class="booking-title-row">


                                <h3>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["bus_number"]
                                    );
                                    ?>

                                </h3>


                                <?php

                                $status_class =
                                    $booking["status"] === "confirmed"
                                    ? "confirmed"
                                    : "cancelled";

                                ?>


                                <span class="status <?php echo $status_class; ?>">

                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $booking["status"]
                                        )
                                    );
                                    ?>

                                </span>


                            </div>


                            <p class="route">

                                <?php

                                echo htmlspecialchars(
                                    $booking["start_point"]
                                );

                                ?>

                                →

                                <?php

                                echo htmlspecialchars(
                                    $booking["end_point"]
                                );

                                ?>

                            </p>


                            <p class="booking-id">

                                Booking ID:

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $booking["booking_id"]
                                    );

                                    ?>

                                </strong>

                            </p>


                        </div>

                    </div>


                    <!-- BOOKING DETAILS -->

                    <div class="booking-details">


                        <div>

                            <span>
                                Date
                            </span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    date(
                                        "M d, Y",
                                        strtotime(
                                            $booking["travel_date"]
                                        )
                                    )
                                );

                                ?>

                            </strong>

                        </div>


                        <div>

                            <span>
                                Departure
                            </span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    date(
                                        "h:i A",
                                        strtotime(
                                            $booking["departure_time"]
                                        )
                                    )
                                );

                                ?>

                            </strong>

                        </div>


                        <div>

                            <span>
                                Seat
                            </span>

                            <strong class="seat">

                                <?php

                                echo htmlspecialchars(
                                    $booking["seat_number"]
                                );

                                ?>

                            </strong>

                        </div>


                    </div>


                    <!-- ACTION -->

                    <div class="booking-actions">


                        <?php if ($booking["status"] === "confirmed"): ?>


                            <a href="ticket.php?booking_id=<?php echo $booking["booking_id"]; ?>"
                               class="view-ticket-btn">

                                View Ticket

                            </a>


                        <?php else: ?>


                            <span class="cancelled-text">

                                Booking Cancelled

                            </span>


                        <?php endif; ?>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php endif; ?>


        <!-- =================================================
             INFORMATION
        ================================================= -->

        <div class="booking-note">

            <strong>
                💡 Booking Information
            </strong>

            <p>
                Please keep your confirmed ticket available
                when boarding the university bus.
            </p>

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

?>