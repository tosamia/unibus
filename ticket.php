<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Bus Ticket | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/ticket.css">

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



    <!-- PAGE HEADER -->

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



    <!-- TICKET -->

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
                        UB-20260815-001
                    </strong>

                </div>



                <!-- ROUTE -->

                <div class="route-section">

                    <div class="route-place">

                        <span>
                            FROM
                        </span>

                        <strong>
                            City
                        </strong>

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
                            SEC Campus
                        </strong>

                    </div>

                </div>



                <!-- JOURNEY DETAILS -->

                <div class="ticket-details">


                    <div class="ticket-detail">

                        <span>
                            Passenger
                        </span>

                        <strong>
                            Samia Saifa
                        </strong>

                    </div>


                    <div class="ticket-detail">

                        <span>
                            Student ID
                        </span>

                        <strong>
                            SEC-12345
                        </strong>

                    </div>


                    <div class="ticket-detail">

                        <span>
                            Bus
                        </span>

                        <strong>
                            BUS-01
                        </strong>

                    </div>


                    <div class="ticket-detail">

                        <span>
                            Seat
                        </span>

                        <strong class="seat-number">
                            A1
                        </strong>

                    </div>


                    <div class="ticket-detail">

                        <span>
                            Date
                        </span>

                        <strong>
                            August 15, 2026
                        </strong>

                    </div>


                    <div class="ticket-detail">

                        <span>
                            Departure
                        </span>

                        <strong>
                            08:00 AM
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
                            Keep this ticket available when boarding the bus.
                        </p>

                    </div>


                    <div class="ticket-code">

                        <div class="fake-barcode">
                            || ||| || |||| ||| ||
                        </div>

                        <span>
                            UB-20260815-001
                        </span>

                    </div>

                </div>

            </div>



            <!-- ACTION BUTTONS -->

            <div class="ticket-actions">

                <button
                    onclick="window.print()"
                    class="print-btn">

                    🖨 Print Ticket

                </button>


                <a
                    href="schedule.php"
                    class="back-btn">

                    ← Back to Schedule

                </a>

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