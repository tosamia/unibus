<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bus | Driver | UniBus</title>

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

            <a href="my-trip.php">
                🛣️ My Trip
            </a>

            <a href="bus-info.php" class="active">
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

                <h1>My Bus</h1>

                <p>
                    View your assigned bus information
                </p>

            </div>

            <div class="driver-profile">
                🧑 Driver
            </div>

        </header>


        <!-- BUS CARD -->

        <section class="driver-section bus-profile-card">

            <div class="bus-image">
                🚌
            </div>

            <div class="bus-profile-content">

                <span class="bus-label">
                    Assigned Bus
                </span>

                <h2>
                    BUS-01
                </h2>

                <p>
                    University Bus
                </p>

                <span class="bus-status">
                    ● Active
                </span>

            </div>

        </section>


        <!-- BUS DETAILS -->

        <section class="driver-section">

            <div class="driver-section-title">

                <h2>
                    Bus Information
                </h2>

                <p>
                    Details of your assigned vehicle
                </p>

            </div>


            <div class="trip-info-grid">

                <div class="trip-info-box">

                    <span>
                        🚌 Bus Number
                    </span>

                    <strong>
                        BUS-01
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🔢 Registration No.
                    </span>

                    <strong>
                        SEC-BUS-001
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        💺 Total Capacity
                    </span>

                    <strong>
                        40 Seats
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        👨‍🎓 Available Seats
                    </span>

                    <strong>
                        40 Seats
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🛣️ Assigned Route
                    </span>

                    <strong>
                        Route A
                    </strong>

                </div>


                <div class="trip-info-box">

                    <span>
                        🔧 Bus Status
                    </span>

                    <strong>
                        Active
                    </strong>

                </div>

            </div>

        </section>


        <!-- DRIVER NOTE -->

        <section class="driver-notice">

            <div class="notice-icon">
                🚌
            </div>

            <div>

                <h3>
                    Vehicle Information
                </h3>

                <p>
                    If there is any problem with the assigned bus,
                    please contact the administrator.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>