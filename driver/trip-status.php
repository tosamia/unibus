<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trip Status | Driver | UniBus</title>

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

            <a href="bus-info.php">
                🚌 My Bus
            </a>

            <a href="trip-status.php" class="active">
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

                <h1>Trip Status</h1>

                <p>
                    Update the current status of your trip
                </p>

            </div>

            <div class="driver-profile">
                🧑 Driver
            </div>

        </header>


        <!-- CURRENT TRIP -->

        <section class="driver-section">

            <div class="driver-section-title">

                <h2>
                    Current Trip
                </h2>

                <p>
                    Your assigned trip for today
                </p>

            </div>


            <div class="current-trip">

                <div class="current-trip-icon">
                    🚌
                </div>

                <div class="current-trip-info">

                    <h3>
                        Campus → Main City
                    </h3>

                    <p>
                        BUS-01 &nbsp; • &nbsp; 8:00 AM
                    </p>

                </div>

                <span class="current-status">
                    Scheduled
                </span>

            </div>

        </section>


        <!-- STATUS OPTIONS -->

        <section class="driver-section">

            <div class="driver-section-title">

                <h2>
                    Update Trip Status
                </h2>

                <p>
                    Select the current status of your trip
                </p>

            </div>


            <div class="status-options">

                <button type="button" class="status-option">

                    <span class="status-option-icon">
                        🕐
                    </span>

                    <span>
                        Scheduled
                    </span>

                </button>


                <button type="button" class="status-option">

                    <span class="status-option-icon">
                        🚌
                    </span>

                    <span>
                        Ready
                    </span>

                </button>


                <button type="button" class="status-option">

                    <span class="status-option-icon">
                        🟢
                    </span>

                    <span>
                        In Progress
                    </span>

                </button>


                <button type="button" class="status-option">

                    <span class="status-option-icon">
                        ✅
                    </span>

                    <span>
                        Completed
                    </span>

                </button>


                <button type="button" class="status-option">

                    <span class="status-option-icon">
                        ⛔
                    </span>

                    <span>
                        Cancelled
                    </span>

                </button>

            </div>

        </section>


        <!-- INFORMATION -->

        <section class="driver-notice">

            <div class="notice-icon">
                ℹ️
            </div>

            <div>

                <h3>
                    Status Information
                </h3>

                <p>
                    Updating the trip status will allow students
                    and the administrator to see the current trip condition.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>