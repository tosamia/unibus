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
   2. GET BOOKING ID
===================================================== */

if (
    !isset($_GET["booking_id"]) ||
    !is_numeric($_GET["booking_id"])
) {

    header("Location: my-bookings.php");
    exit;

}

$booking_id = (int) $_GET["booking_id"];


/* =====================================================
   3. GET BOOKING FROM DATABASE
===================================================== */

$sql = "SELECT

            bookings.id AS booking_id,
            bookings.seat_number,
            bookings.booking_date,
            bookings.status AS booking_status,

            schedules.travel_date,
            schedules.departure_time,
            schedules.arrival_time,

            buses.bus_number,
            buses.bus_name,

            routes.route_name,
            routes.start_point,
            routes.end_point,

            users.name,
            users.student_id,
            users.email

        FROM bookings

        INNER JOIN schedules
            ON bookings.schedule_id = schedules.id

        INNER JOIN buses
            ON schedules.bus_id = buses.id

        INNER JOIN routes
            ON schedules.route_id = routes.id

        INNER JOIN users
            ON bookings.user_id = users.id

        WHERE bookings.id = ?
        AND bookings.user_id = ?

        LIMIT 1";


$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Ticket query error: " . $conn->error);

}


$stmt->bind_param(
    "ii",
    $booking_id,
    $user_id
);


$stmt->execute();

$result = $stmt->get_result();

$ticket = $result->fetch_assoc();

$stmt->close();


/* =====================================================
   4. CHECK BOOKING
===================================================== */

if (!$ticket) {

    die("Booking not found.");

}


/* =====================================================
   5. CHECK STATUS
===================================================== */

if ($ticket["booking_status"] !== "confirmed") {

    die("This booking is not confirmed.");

}


/* =====================================================
   6. FORMAT INFORMATION
===================================================== */

$ticket_id = "UB-" . str_pad(
    $ticket["booking_id"],
    6,
    "0",
    STR_PAD_LEFT
);


$travel_date = date(
    "F d, Y",
    strtotime($ticket["travel_date"])
);


$departure_time = date(
    "h:i A",
    strtotime($ticket["departure_time"])
);


$arrival_time = date(
    "h:i A",
    strtotime($ticket["arrival_time"])
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bus Ticket | UniBus</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <link
        rel="stylesheet"
        href="css/ticket.css"
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

        <a href="student-dashboard.php">
            Dashboard
        </a>

        <a href="schedule.php">
            Bus Schedule
        </a>

        <a href="my-bookings.php">
            My Bookings
        </a>

        <a href="contact.php">
            Contact
        </a>

    </div>


    <div class="nav-buttons">

        <a
            href="profile.php"
            class="profile-btn"
        >
            👤 Profile
        </a>


        <a
            href="logout.php"
            class="logout-btn"
        >
            Logout
        </a>

    </div>

</nav>



<!-- =================================================
     PAGE HEADER
================================================= -->

<section class="ticket-header">

    <span class="section-label">
        BOOKING CONFIRMED
    </span>


    <h1>
        Your Bus Ticket
    </h1>


    <p>
        Your seat has been successfully reserved.
    </p>

</section>



<!-- =================================================
     TICKET SECTION
================================================= -->

<section class="ticket-section">

    <div class="ticket-container">


        <!-- SUCCESS MESSAGE -->

        <div class="success-message">

            <div class="success-icon">
                ✓
            </div>


            <div>

                <h2>
                    Booking Confirmed
                </h2>

                <p>
                    Your bus seat has been successfully booked.
                </p>

            </div>

        </div>



        <!-- DIGITAL TICKET -->

        <div class="ticket-card">


            <!-- TICKET TOP -->

            <div class="ticket-top">

                <div>

                    <div class="ticket-logo">
                        🚌 UniBus
                    </div>

                    <span>
                        UNIVERSITY BUS TICKET
                    </span>

                </div>


                <div class="booking-status">
                    CONFIRMED
                </div>

            </div>



            <!-- BOOKING ID -->

            <div class="booking-id">

                <span>
                    Booking ID
                </span>


                <strong>

                    <?php
                    echo htmlspecialchars($ticket_id);
                    ?>

                </strong>

            </div>



            <!-- ROUTE -->

            <div class="route-section">


                <div class="route-place">

                    <span>
                        FROM
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ticket["start_point"]
                        );
                        ?>

                    </strong>


                    <small>

                        <?php
                        echo htmlspecialchars(
                            $departure_time
                        );
                        ?>

                    </small>

                </div>



                <div class="route-line">

                    <span>
                        🚌
                    </span>

                    <div></div>

                </div>



                <div class="route-place">

                    <span>
                        TO
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ticket["end_point"]
                        );
                        ?>

                    </strong>


                    <small>

                        <?php
                        echo htmlspecialchars(
                            $arrival_time
                        );
                        ?>

                    </small>

                </div>

            </div>



            <!-- JOURNEY DETAILS -->

            <div class="ticket-details">


                <!-- PASSENGER -->

                <div class="ticket-detail">

                    <span>
                        Passenger
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ticket["name"]
                        );
                        ?>

                    </strong>

                </div>



                <!-- STUDENT ID -->

                <div class="ticket-detail">

                    <span>
                        Student ID
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ticket["student_id"]
                        );
                        ?>

                    </strong>

                </div>



                <!-- BUS -->

                <div class="ticket-detail">

                    <span>
                        Bus
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ticket["bus_number"]
                        );
                        ?>

                    </strong>

                </div>



                <!-- SEAT -->

                <div class="ticket-detail">

                    <span>
                        Seat
                    </span>


                    <strong class="seat-number">

                        <?php
                        echo htmlspecialchars(
                            $ticket["seat_number"]
                        );
                        ?>

                    </strong>

                </div>



                <!-- DATE -->

                <div class="ticket-detail">

                    <span>
                        Date
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $travel_date
                        );
                        ?>

                    </strong>

                </div>



                <!-- DEPARTURE -->

                <div class="ticket-detail">

                    <span>
                        Departure
                    </span>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $departure_time
                        );
                        ?>

                    </strong>

                </div>

            </div>



            <!-- DIVIDER -->

            <div class="ticket-divider"></div>



            <!-- TICKET FOOTER -->

            <div class="ticket-footer">


                <div>

                    <strong>
                        Please arrive before departure.
                    </strong>


                    <p>
                        Keep this ticket available
                        when boarding the bus.
                    </p>

                </div>


                <div class="ticket-code">

                    <div class="fake-barcode">
                        || ||| || |||| ||| ||
                    </div>


                    <span>

                        <?php
                        echo htmlspecialchars($ticket_id);
                        ?>

                    </span>

                </div>

            </div>

        </div>



        <!-- ACTION BUTTONS -->

        <div class="ticket-actions">


            <button
                type="button"
                onclick="window.print()"
                class="print-btn"
            >

                🖨 Print Ticket

            </button>


            <a
                href="my-bookings.php"
                class="back-btn"
            >

                ← My Bookings

            </a>


            <a
                href="student-dashboard.php"
                class="back-btn"
            >

                Dashboard

            </a>

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

            <a href="student-dashboard.php">
                Dashboard
            </a>

            <a href="schedule.php">
                Bus Schedule
            </a>

            <a href="my-bookings.php">
                My Bookings
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