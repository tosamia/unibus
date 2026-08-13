<?php

/*
|--------------------------------------------------------------------------
| UPCOMING TRIP DATA
|--------------------------------------------------------------------------
| For now these are sample values.
| Later Samia's backend can get these values from MySQL.
*/

$tripDate = "2026-08-15";
$tripDateFormatted = date("M d, Y", strtotime($tripDate));

$busNumber = "BUS-01";
$seatNumber = "A1";

$from = "City";
$to = "SEC Campus";

$departureTime = "08:00 AM";

$bookingId = "UB-20260815-001";

$bookingStatus = "Confirmed";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/student-dashboard.css">

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <div class="logo">
        🚌 UniBus
    </div>


    <div class="nav-links">

        <a href="student-dashboard.php" class="active">
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

        <a href="profile.php" class="profile-btn">
            👤 Profile
        </a>

        <a href="login.php" class="logout-btn">
            Logout
        </a>

    </div>

</nav>



<!-- =========================
     DASHBOARD HEADER
========================= -->

<section class="dashboard-header">

    <div class="dashboard-container">

        <span class="section-label">
            STUDENT DASHBOARD
        </span>


        <h1>
            Welcome back, Samia! 👋
        </h1>


        <p>
            Manage your university bus bookings
            and check your upcoming trips.
        </p>

    </div>

</section>



<!-- =========================
     MAIN DASHBOARD
========================= -->

<section class="dashboard-section">

    <div class="dashboard-container">


        <!-- =========================
             QUICK ACTIONS
        ========================== -->

        <div class="section-heading">

            <div>

                <h2>
                    Quick Actions
                </h2>

                <p>
                    What would you like to do?
                </p>

            </div>

        </div>



        <div class="quick-actions">


            <!-- BOOK A SEAT -->

            <a href="schedule.php" class="action-card">

                <div class="action-icon">
                    🚌
                </div>


                <div>

                    <h3>
                        Book a Seat
                    </h3>

                    <p>
                        Find a bus and reserve your seat.
                    </p>

                </div>


                <span class="arrow">
                    →
                </span>

            </a>



            <!-- MY BOOKINGS -->

            <a href="my-bookings.php" class="action-card">

                <div class="action-icon">
                    🎫
                </div>


                <div>

                    <h3>
                        My Bookings
                    </h3>

                    <p>
                        View your booking records and tickets.
                    </p>

                </div>


                <span class="arrow">
                    →
                </span>

            </a>



            <!-- BUS SCHEDULE -->

            <a href="schedule.php" class="action-card">

                <div class="action-icon">
                    🕐
                </div>


                <div>

                    <h3>
                        Bus Schedule
                    </h3>

                    <p>
                        Check routes and departure times.
                    </p>

                </div>


                <span class="arrow">
                    →
                </span>

            </a>



            <!-- PROFILE -->

            <a href="profile.php" class="action-card">

                <div class="action-icon">
                    👤
                </div>


                <div>

                    <h3>
                        My Profile
                    </h3>

                    <p>
                        View and manage your student information.
                    </p>

                </div>


                <span class="arrow">
                    →
                </span>

            </a>

        </div>



        <!-- =========================
             UPCOMING TRIP + PROFILE
        ========================== -->

        <div class="dashboard-grid">


            <!-- =========================
                 UPCOMING TRIP
            ========================== -->

            <div class="upcoming-card">


                <div class="card-heading">

                    <div>

                        <h2>
                            Upcoming Trip
                        </h2>

                        <p>
                            Your next bus journey
                        </p>

                    </div>


                    <span class="confirmed-status">
                        <?php echo htmlspecialchars($bookingStatus); ?>
                    </span>

                </div>



                <!-- ROUTE -->

                <div class="trip-route">


                    <div class="trip-location">

                        <span>
                            FROM
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($from); ?>
                        </strong>

                        <small>
                            07:30 AM
                        </small>

                    </div>



                    <div class="trip-arrow">

                        🚌

                        <div></div>

                    </div>



                    <div class="trip-location">

                        <span>
                            TO
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($to); ?>
                        </strong>

                        <small>
                            <?php echo htmlspecialchars($departureTime); ?>
                        </small>

                    </div>

                </div>



                <!-- TRIP DETAILS -->

                <div class="trip-details">


                    <!-- TRIP DATE -->

                    <div>

                        <span>
                            Trip Date
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($tripDateFormatted); ?>
                        </strong>

                    </div>



                    <!-- BUS -->

                    <div>

                        <span>
                            Bus
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($busNumber); ?>
                        </strong>

                    </div>



                    <!-- SEAT -->

                    <div>

                        <span>
                            Seat
                        </span>

                        <strong class="blue-text">
                            <?php echo htmlspecialchars($seatNumber); ?>
                        </strong>

                    </div>

                </div>



                <!-- BOOKING ID -->

                <div class="booking-reference">

                    <span>
                        Booking ID:
                    </span>

                    <strong>
                        <?php echo htmlspecialchars($bookingId); ?>
                    </strong>

                </div>



                <!-- VIEW TICKET -->

                <a href="ticket.php" class="ticket-link">

                    View My Ticket →

                </a>

            </div>



            <!-- =========================
                 PROFILE SUMMARY
            ========================== -->

            <div class="profile-summary">


                <div class="card-heading">

                    <div>

                        <h2>
                            My Profile
                        </h2>

                        <p>
                            Student information
                        </p>

                    </div>


                    <a href="profile.php">
                        Edit
                    </a>

                </div>



                <div class="student-avatar">
                    👤
                </div>



                <h3>
                    Samia Saifa
                </h3>


                <p class="student-id">
                    Student ID: SEC-12345
                </p>



                <div class="profile-info">


                    <div>

                        <span>
                            Department
                        </span>

                        <strong>
                            Computer Science
                        </strong>

                    </div>


                    <div>

                        <span>
                            Email
                        </span>

                        <strong>
                            samia@example.com
                        </strong>

                    </div>

                </div>

            </div>

        </div>



        <!-- =========================
             RECENT BOOKINGS
        ========================== -->

        <div class="recent-section">


            <div class="section-heading">

                <div>

                    <h2>
                        Recent Bookings
                    </h2>

                    <p>
                        Your latest reservations
                    </p>

                </div>


                <a href="my-bookings.php">
                    View All →
                </a>

            </div>



            <div class="recent-table">


                <!-- TABLE HEADER -->

                <div class="table-row table-header">

                    <span>
                        Booking ID
                    </span>

                    <span>
                        Bus
                    </span>

                    <span>
                        Date
                    </span>

                    <span>
                        Seat
                    </span>

                    <span>
                        Status
                    </span>

                </div>



                <!-- BOOKING -->

                <div class="table-row">

                    <span>
                        <?php echo htmlspecialchars($bookingId); ?>
                    </span>


                    <span>
                        <?php echo htmlspecialchars($busNumber); ?>
                    </span>


                    <span>
                        <?php echo htmlspecialchars($tripDateFormatted); ?>
                    </span>


                    <span class="seat-value">
                        <?php echo htmlspecialchars($seatNumber); ?>
                    </span>


                    <span class="confirmed-status">
                        <?php echo htmlspecialchars($bookingStatus); ?>
                    </span>

                </div>


            </div>

        </div>


    </div>

</section>



<!-- =========================
     FOOTER
========================= -->

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