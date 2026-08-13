<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Bookings | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/my-bookings.css">

</head>

<body>

    <!-- NAVBAR -->

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

            <a href="login.php"
                class="login-btn">
                Login
            </a>

            <a href="register.php"
                class="register-btn">
                Register
            </a>

        </div>

    </nav>


    <!-- HEADER -->

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


    <!-- BOOKINGS -->

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


            <!-- BOOKING CARD 1 -->

            <div class="booking-card">

                <div class="booking-main">

                    <div class="booking-icon">
                        🚌
                    </div>

                    <div class="booking-info">

                        <div class="booking-title-row">

                            <h3>
                                BUS-01
                            </h3>

                            <span class="status confirmed">
                                Confirmed
                            </span>

                        </div>

                        <p class="route">
                            City → SEC Campus
                        </p>

                        <p class="booking-id">
                            Booking ID:
                            <strong>
                                UB-20260815-001
                            </strong>
                        </p>

                    </div>

                </div>


                <div class="booking-details">

                    <div>

                        <span>
                            Date
                        </span>

                        <strong>
                            Aug 15, 2026
                        </strong>

                    </div>

                    <div>

                        <span>
                            Departure
                        </span>

                        <strong>
                            08:00 AM
                        </strong>

                    </div>

                    <div>

                        <span>
                            Seat
                        </span>

                        <strong class="seat">
                            A1
                        </strong>

                    </div>

                </div>


                <div class="booking-actions">

                    <a href="ticket.php"
                        class="view-ticket-btn">

                        View Ticket

                    </a>

                </div>

            </div>


            <!-- BOOKING CARD 2 -->

            <div class="booking-card">

                <div class="booking-main">

                    <div class="booking-icon">
                        🚌
                    </div>

                    <div class="booking-info">

                        <div class="booking-title-row">

                            <h3>
                                BUS-03
                            </h3>

                            <span class="status confirmed">
                                Confirmed
                            </span>

                        </div>

                        <p class="route">
                            SEC Campus → City
                        </p>

                        <p class="booking-id">
                            Booking ID:
                            <strong>
                                UB-20260816-002
                            </strong>
                        </p>

                    </div>

                </div>


                <div class="booking-details">

                    <div>

                        <span>
                            Date
                        </span>

                        <strong>
                            Aug 16, 2026
                        </strong>

                    </div>

                    <div>

                        <span>
                            Departure
                        </span>

                        <strong>
                            04:00 PM
                        </strong>

                    </div>

                    <div>

                        <span>
                            Seat
                        </span>

                        <strong class="seat">
                            B3
                        </strong>

                    </div>

                </div>


                <div class="booking-actions">

                    <a href="ticket.php"
                        class="view-ticket-btn">

                        View Ticket

                    </a>

                </div>

            </div>


            <!-- BOOKING CARD 3 -->

            <div class="booking-card">

                <div class="booking-main">

                    <div class="booking-icon">
                        🚌
                    </div>

                    <div class="booking-info">

                        <div class="booking-title-row">

                            <h3>
                                BUS-02
                            </h3>

                            <span class="status cancelled">
                                Cancelled
                            </span>

                        </div>

                        <p class="route">
                            City → SEC Campus
                        </p>

                        <p class="booking-id">
                            Booking ID:
                            <strong>
                                UB-20260818-003
                            </strong>
                        </p>

                    </div>

                </div>


                <div class="booking-details">

                    <div>

                        <span>
                            Date
                        </span>

                        <strong>
                            Aug 18, 2026
                        </strong>

                    </div>

                    <div>

                        <span>
                            Departure
                        </span>

                        <strong>
                            09:00 AM
                        </strong>

                    </div>

                    <div>

                        <span>
                            Seat
                        </span>

                        <strong class="seat">
                            C2
                        </strong>

                    </div>

                </div>


                <div class="booking-actions">

                    <span class="cancelled-text">
                        Booking Cancelled
                    </span>

                </div>

            </div>


            <!-- INFORMATION -->

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


    <!-- FOOTER -->

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