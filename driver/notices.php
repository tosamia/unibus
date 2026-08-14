<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notices | Driver | UniBus</title>

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

            <a href="trip-status.php">
                🔄 Trip Status
            </a>

            <a href="notices.php" class="active">
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

                <h1>Notices</h1>

                <p>
                    Important announcements for drivers
                </p>

            </div>

            <div class="driver-profile">
                🧑 Driver
            </div>

        </header>


        <!-- NOTICE LIST -->

        <section class="driver-section">

            <div class="driver-section-title">

                <h2>
                    Latest Notices
                </h2>

                <p>
                    Important information from the administration
                </p>

            </div>


            <div class="driver-notice-list">


                <!-- OFF DAY -->

                <article class="driver-notice-item">

                    <div class="notice-item-icon">
                        📅
                    </div>

                    <div class="notice-item-content">

                        <div class="notice-item-top">

                            <h3>
                                University Holiday
                            </h3>

                            <span class="notice-badge holiday">
                                Off-Day
                            </span>

                        </div>

                        <p>
                            University bus service will remain closed
                            on the announced university holiday.
                        </p>

                        <small>
                            Off-Day Date: Not assigned
                        </small>

                    </div>

                </article>


                <!-- BUS UPDATE -->

                <article class="driver-notice-item">

                    <div class="notice-item-icon">
                        🚌
                    </div>

                    <div class="notice-item-content">

                        <div class="notice-item-top">

                            <h3>
                                Bus Service Update
                            </h3>

                            <span class="notice-badge update">
                                Bus Update
                            </span>

                        </div>

                        <p>
                            Please check your assigned bus before
                            starting the daily trip.
                        </p>

                        <small>
                            Date: Not assigned
                        </small>

                    </div>

                </article>


                <!-- GENERAL NOTICE -->

                <article class="driver-notice-item">

                    <div class="notice-item-icon">
                        📢
                    </div>

                    <div class="notice-item-content">

                        <div class="notice-item-top">

                            <h3>
                                General Notice
                            </h3>

                            <span class="notice-badge general">
                                General
                            </span>

                        </div>

                        <p>
                            No new general announcements at the moment.
                        </p>

                        <small>
                            Date: Not assigned
                        </small>

                    </div>

                </article>


            </div>

        </section>


        <!-- INFORMATION -->

        <section class="driver-notice">

            <div class="notice-icon">
                ℹ️
            </div>

            <div>

                <h3>
                    Stay Updated
                </h3>

                <p>
                    Check this page regularly for holiday,
                    off-day and bus service announcements.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>