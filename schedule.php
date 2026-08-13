<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bus Schedule | UniBus</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/schedule.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo">
            🚌 UniBus
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="schedule.php" class="active">Bus Schedule</a>
            <a href="notices.php">Notices</a>
            <a href="contact.php">Contact</a>
        </div>

        <div class="nav-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>

    </nav>


    <!-- PAGE HEADER -->

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


    <!-- SEARCH / FILTER -->

    <section class="schedule-section">

        <div class="schedule-container">

            <div class="schedule-filter">

                <div class="filter-group">

                    <label for="date">
                        Select Date
                    </label>

                    <input type="date" id="date">

                </div>


                <div class="filter-group">

                    <label for="route">
                        Select Route
                    </label>

                    <select id="route">

                        <option value="">
                            All Routes
                        </option>

                        <option>
                            Campus Route A
                        </option>

                        <option>
                            Campus Route B
                        </option>

                        <option>
                            Campus Route C
                        </option>

                    </select>

                </div>


                <button class="filter-btn">
                    Search Schedule
                </button>

            </div>


            <!-- SCHEDULE CARDS -->

            <div class="schedule-list">


                <!-- BUS 1 -->

                <div class="schedule-card">

                    <div class="bus-info">

                        <div class="bus-icon">
                            🚌
                        </div>

                        <div>

                            <h3>
                                BUS-01
                            </h3>

                            <p>
                                Campus Route A
                            </p>

                        </div>

                    </div>


                    <div class="route-info">

                        <div>

                            <span>
                                Departure
                            </span>

                            <strong>
                                08:00 AM
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
                                09:00 AM
                            </strong>

                        </div>

                    </div>


                    <div class="schedule-action">

                        <span class="available">
                            ● Available
                        </span>

                        <a href="login.php" class="book-btn">
                            Book Seat
                        </a>

                    </div>

                </div>


                <!-- BUS 2 -->

                <div class="schedule-card">

                    <div class="bus-info">

                        <div class="bus-icon">
                            🚌
                        </div>

                        <div>

                            <h3>
                                BUS-02
                            </h3>

                            <p>
                                Campus Route B
                            </p>

                        </div>

                    </div>


                    <div class="route-info">

                        <div>

                            <span>
                                Departure
                            </span>

                            <strong>
                                09:00 AM
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
                                10:00 AM
                            </strong>

                        </div>

                    </div>


                    <div class="schedule-action">

                        <span class="available">
                            ● Available
                        </span>

                        <a href="login.php" class="book-btn">
                            Book Seat
                        </a>

                    </div>

                </div>


                <!-- BUS 3 -->

                <div class="schedule-card">

                    <div class="bus-info">

                        <div class="bus-icon">
                            🚌
                        </div>

                        <div>

                            <h3>
                                BUS-03
                            </h3>

                            <p>
                                Campus Route C
                            </p>

                        </div>

                    </div>


                    <div class="route-info">

                        <div>

                            <span>
                                Departure
                            </span>

                            <strong>
                                04:00 PM
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
                                05:00 PM
                            </strong>

                        </div>

                    </div>


                    <div class="schedule-action">

                        <span class="available">
                            ● Available
                        </span>

                        <a href="login.php" class="book-btn">
                            Book Seat
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- FOOTER -->

    <footer class="footer">

        <div class="footer-content">

            <div>

                <h3>🚌 UniBus</h3>

                <p>
                    University Bus Booking and Management System
                </p>

            </div>


            <div>

                <h3>Quick Links</h3>

                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="schedule.php">Bus Schedule</a>
                <a href="notices.php">Notices</a>

            </div>


            <div>

                <h3>Contact Info</h3>

                <p>📞 +880 1712-345678</p>
                <p>✉ info@unibus.sec.edu.bd</p>
                <p>📍 Sylhet Engineering College</p>

            </div>

        </div>


        <div class="footer-bottom">
            © 2026 UniBus. All rights reserved.
        </div>

    </footer>

</body>

</html>