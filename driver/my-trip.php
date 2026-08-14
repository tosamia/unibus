<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Trip | Driver | UniBus</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="driver-layout">

    <!-- SIDEBAR -->

    <aside class="driver-sidebar">

        <div class="driver-logo">
            🚌 UniBus
        </div>

        <p class="driver-role">
            Driver Panel
        </p>

        <nav>

            <a href="driver-dashboard.php">
                📊 Dashboard
            </a>

            <a href="my-trip.php" class="active">
                🛣️ My Trip
            </a>

            <a href="bus-info.php">
                🚌 My Bus
            </a>

            <a href="trip-status.php">
                🔄 Trip Status
            </a>

            <a href="../notices.php">
                📢 Notices
            </a>

        </nav>

        <a href="../login.php" class="driver-logout">
            ↪ Logout
        </a>

    </aside>


    <!-- MAIN -->

    <main class="driver-main">

        <header class="driver-header">

            <div>

                <h1>My Trip</h1>

                <p>
                    View your assigned trip information
                </p>

            </div>

            <div class="driver-profile">
                🧑 Driver
            </div>

        </header>


        <!-- TRIP INFORMATION -->

        <section class="driver-trip-card">

            <div class="trip-title">

                <div>

                    <span class="trip-icon">
                        🚌
                    </span>

                    <div>

                        <h2>
                            Today's Assigned Trip
                        </h2>

                        <p>
                            Your current bus schedule
                        </p>

                    </div>

                </div>

                <span class="trip-status">
                    Scheduled
                </span>

            </div>


            <div class="trip-details">

                <div>

                    <span>
                        Route
                    </span>

                    <strong>
                        Campus → Main City
                    </strong>

                </div>


                <div>

                    <span>
                        Bus
                    </span>

                    <strong>
                        BUS-01
                    </strong>

                </div>


                <div>

                    <span>
                        Departure
                    </span>

                    <strong>
                        8:00 AM
                    </strong>

                </div>


                <div>

                    <span>
                        Arrival
                    </span>

                    <strong>
                        8:45 AM
                    </strong>

                </div>

            </div>

        </section>


        <!-- TRIP DETAILS -->

        <section class="driver-section">

            <div class="driver-section-title">

                <h2>
                    Trip Details
                </h2>

                <p>
                    Information about your assigned journey
                </p>

            </div>


            <div class="trip-info-grid">

                <div class="trip-info-box">

                    <span>
                        📅 Trip Date
                    </span>

                    <strong>
                        Not assigned
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        📍 Starting Point
                    </span>

                    <strong>
                        University Campus
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🏁 Destination
                    </span>

                    <strong>
                        Main City
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🛑 Number of Stops
                    </span>

                    <strong>
                        5 Stops
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🎫 Booked Seats
                    </span>

                    <strong>
                        0
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        💺 Available Seats
                    </span>

                    <strong>
                        40
                    </strong>

                </div>

            </div>

        </section>


        <!-- TRIP ACTION -->

        <section class="driver-notice">

            <div class="notice-icon">
                ℹ️
            </div>

            <div>

                <h3>
                    Before Starting Your Trip
                </h3>

                <p>
                    Please check your assigned bus and update the trip
                    status when you are ready to depart.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>