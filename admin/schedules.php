<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Schedules | Admin | UniBus</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->

    <aside class="admin-sidebar">

        <div class="admin-logo">
            🚌 UniBus
        </div>

        <p class="admin-role">
            Administration
        </p>

        <nav>

            <a href="admin-dashboard.php">
                📊 Dashboard
            </a>

            <a href="students.php">
                👨‍🎓 Students
            </a>

            <a href="drivers.php">
                🧑Drivers
            </a>

            <a href="buses.php">
                🚌 Buses
            </a>

            <a href="routes.php">
                🛣️ Routes
            </a>

            <a href="schedules.php" class="active">
                🕐 Schedules
            </a>

            <a href="bookings.php">
                🎫 Bookings
            </a>

            <a href="notices.php">
                📢 Notices
            </a>

        </nav>

        <a href="../login.php" class="admin-logout">
            ↪ Logout
        </a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="admin-main">

        <header class="admin-header">

            <div>

                <h1>Schedules</h1>

                <p>
                    Manage bus schedules and trips
                </p>

            </div>

            <div class="admin-profile">
                👨‍💼 Admin
            </div>

        </header>


        <section class="admin-section">

            <div class="student-toolbar">

                <div>

                    <h2>
                        Schedule List
                    </h2>

                    <p>
                        View and manage scheduled bus trips
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search schedule..."
                    >

                    <button type="button">
                        + Add Schedule
                    </button>

                </div>

            </div>


            <!-- SCHEDULE TABLE -->

            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Route</th>

                            <th>Bus</th>

                            <th>Departure</th>

                            <th>Arrival</th>

                            <th>Trip Date</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="8" class="no-data">

                                <div>
                                    🕐
                                </div>

                                <strong>
                                    No schedules found
                                </strong>

                                <p>
                                    Scheduled trips will appear here.
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>

</html>