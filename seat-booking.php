<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Select Seat | UniBus</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/seat-booking.css">
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
            <a href="schedule.php">Bus Schedule</a>
            <a href="notices.php">Notices</a>
            <a href="contact.php">Contact</a>
        </div>

        <div class="nav-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>

    </nav>


    <!-- PAGE HEADER -->

    <section class="booking-header">

        <span class="section-label">
            SEAT BOOKING
        </span>

        <h1>
            Select Your Seat
        </h1>

        <p>
            Choose an available seat for your journey.
        </p>

    </section>


    <!-- BOOKING SECTION -->

    <section class="seat-section">

        <div class="seat-container">


            <!-- BUS INFORMATION -->

            <div class="bus-summary">

                <div>

                    <h2>
                        BUS-01
                    </h2>

                    <p>
                        Campus Route A
                    </p>

                </div>

                <div class="trip-info">

                    <span>
                        📅 August 15, 2026
                    </span>

                    <span>
                        🕐 08:00 AM
                    </span>

                </div>

            </div>


            <!-- MAIN BOOKING AREA -->

            <div class="booking-layout">


                <!-- SEAT AREA -->

                <div class="seat-card">

                    <div class="bus-front">
                        🚍 FRONT
                    </div>


                    <div class="seat-layout">


                        <!-- ROW A -->

                        <div class="seat-row">

                            <button class="seat available-seat">
                                A1
                            </button>

                            <button class="seat available-seat">
                                A2
                            </button>

                            <div class="aisle"></div>

                            <button class="seat booked-seat" disabled>
                                A3
                            </button>

                            <button class="seat available-seat">
                                A4
                            </button>

                        </div>


                        <!-- ROW B -->

                        <div class="seat-row">

                            <button class="seat available-seat">
                                B1
                            </button>

                            <button class="seat booked-seat" disabled>
                                B2
                            </button>

                            <div class="aisle"></div>

                            <button class="seat available-seat">
                                B3
                            </button>

                            <button class="seat available-seat">
                                B4
                            </button>

                        </div>


                        <!-- ROW C -->

                        <div class="seat-row">

                            <button class="seat available-seat">
                                C1
                            </button>

                            <button class="seat available-seat">
                                C2
                            </button>

                            <div class="aisle"></div>

                            <button class="seat available-seat">
                                C3
                            </button>

                            <button class="seat booked-seat" disabled>
                                C4
                            </button>

                        </div>


                        <!-- ROW D -->

                        <div class="seat-row">

                            <button class="seat available-seat">
                                D1
                            </button>

                            <button class="seat available-seat">
                                D2
                            </button>

                            <div class="aisle"></div>

                            <button class="seat available-seat">
                                D3
                            </button>

                            <button class="seat available-seat">
                                D4
                            </button>

                        </div>


                        <!-- ROW E -->

                        <div class="seat-row">

                            <button class="seat booked-seat" disabled>
                                E1
                            </button>

                            <button class="seat available-seat">
                                E2
                            </button>

                            <div class="aisle"></div>

                            <button class="seat available-seat">
                                E3
                            </button>

                            <button class="seat available-seat">
                                E4
                            </button>

                        </div>

                    </div>

                </div>


                <!-- BOOKING SUMMARY -->

                <div class="booking-summary">

                    <h2>
                        Booking Summary
                    </h2>


                    <div class="summary-item">

                        <span>
                            Bus
                        </span>

                        <strong>
                            BUS-01
                        </strong>

                    </div>


                    <div class="summary-item">

                        <span>
                            Route
                        </span>

                        <strong>
                            Campus Route A
                        </strong>

                    </div>


                    <div class="summary-item">

                        <span>
                            Date
                        </span>

                        <strong>
                            Aug 15, 2026
                        </strong>

                    </div>


                    <div class="summary-item">

                        <span>
                            Time
                        </span>

                        <strong>
                            08:00 AM
                        </strong>

                    </div>


                    <div class="selected-seat">

                        <span>
                            Selected Seat
                        </span>

                        <strong id="selectedSeat">
                            None
                        </strong>

                    </div>


                    <button class="confirm-btn">
                        Confirm Booking
                    </button>

                </div>

            </div>


            <!-- LEGEND -->

            <div class="seat-legend">

                <div>
                    <span class="legend-box available-box"></span>
                    Available
                </div>

                <div>
                    <span class="legend-box selected-box"></span>
                    Selected
                </div>

                <div>
                    <span class="legend-box booked-box"></span>
                    Booked
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


    <!-- TEMPORARY FRONTEND SEAT SELECTION -->

    <script>

        const seats = document.querySelectorAll(".available-seat");

        const selectedSeat = document.getElementById("selectedSeat");

        seats.forEach(function(seat) {

            seat.addEventListener("click", function() {

                seats.forEach(function(item) {
                    item.classList.remove("selected-seat-button");
                });

                seat.classList.add("selected-seat-button");

                selectedSeat.textContent = seat.textContent;

            });

        });

    </script>

</body>

</html>