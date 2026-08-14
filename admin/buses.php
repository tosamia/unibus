<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buses | Admin | UniBus</title>

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

            <a href="buses.php" class="active">
                🚌 Buses
            </a>

            <a href="routes.php">
                🛣️ Routes
            </a>

            <a href="schedules.php">
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

                <h1>Buses</h1>

                <p>
                    Manage UniBus vehicles
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
                        Bus List
                    </h2>

                    <p>
                        View and manage registered buses
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search bus..."
                    >

                    <button type="button">
                        + Add Bus
                    </button>

                </div>

            </div>


            <!-- BUS TABLE -->

            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Bus Number</th>

                            <th>Registration No.</th>

                            <th>Capacity</th>

                            <th>Driver</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="7" class="no-data">

                                <div>
                                    🚌
                                </div>

                                <strong>
                                    No buses found
                                </strong>

                                <p>
                                    Bus records will appear here.
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