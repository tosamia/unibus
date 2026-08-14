<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Drivers | Admin | UniBus</title>

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

            <a href="drivers.php" class="active">
                🧑‍✈️ Drivers
            </a>

            <a href="buses.php">
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

                <h1>Drivers</h1>

                <p>
                    Manage UniBus drivers
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
                        Driver List
                    </h2>

                    <p>
                        View and manage registered drivers
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search driver..."
                    >

                    <button type="button">
                        + Add Driver
                    </button>

                </div>

            </div>


            <!-- DRIVER TABLE -->

            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Driver Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>License No.</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="7" class="no-data">

                                <div>
                                    🧑
                                </div>

                                <strong>
                                    No drivers found
                                </strong>

                                <p>
                                    Driver records will appear here.
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