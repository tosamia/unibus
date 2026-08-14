<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Driver Dashboard | UniBus</title>

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

            <a href="driver-dashboard.php" class="active">
                📊 Dashboard
            </a>

            <a href="my-trip.php">
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


    <!-- MAIN CONTENT -->

    <main class="driver-main">

        <header class="driver-header">

            <div>

                <h1>Driver Dashboard</h1>

                <p>
                    Welcome back, Driver!
                </p>

            </div>

            <div class="driver-profile">
                🧑 Driver
            </div>

        </header>


        <!-- TODAY'S TRIP -->

        <section class="driver-trip-card">

            <div class="trip-title">

                <div>
                    <span class="trip-icon">🚌</span>

                    <div>
                        <h2>Today's Trip</h2>

                        <p>
                            Your assigned bus trip
                        </p>
                    </div>
                </div>

                <span class="trip-status">
                    Scheduled
                </span>

            </div>


            <div class="trip-details">

                <div>

                    <span>Route</span>

                    <strong>
                        Campus → Main City
                    </strong>

                </div>


                <div>

                    <span>Bus</span>

                    <strong>
                        BUS-01
                    </strong>

                </div>


                <div>

                    <span>Departure</span>

                    <strong>
                        8:00 AM
                    </strong>

                </div>


                <div>

                    <span>Arrival</span>

                    <strong>
                        8:45 AM
                    </strong>

                </div>

            </div>


            <div class="trip-actions">

                <a href="my-trip.php">
                    View Trip
                </a>

                <a href="trip-status.php">
                    Update Status
                </a>

            </div>

        </section>


        <!-- QUICK INFORMATION -->

        <section class="driver-stats">

            <div class="driver-stat-card">

                <span>🚌</span>

                <div>

                    <p>Assigned Bus</p>

                    <h2>BUS-01</h2>

                </div>

            </div>


            <div class="driver-stat-card">

                <span>🛣️</span>

                <div>

                    <p>Assigned Route</p>

                    <h2>Route A</h2>

                </div>

            </div>


            <div class="driver-stat-card">

                <span>🎫</span>

                <div>

                    <p>Today's Bookings</p>

                    <h2>0</h2>

                </div>

            </div>

        </section>


        <!-- NOTICE -->

        <section class="driver-notice">

            <div class="notice-icon">
                📢
            </div>

            <div>

                <h3>Important Notice</h3>

                <p>
                    No new notices at the moment.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>