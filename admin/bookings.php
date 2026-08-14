<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bookings | Admin | UniBus</title>

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
                🧑 Drivers
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

            <a href="bookings.php" class="active">
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

                <h1>Bookings</h1>

                <p>
                    View and manage student bus bookings
                </p>

            </div>

            <div class="admin-profile">
                👨‍💼 Admin
            </div>

        </header>


        <!-- BOOKING SUMMARY -->

        <section class="admin-stats">

            <div class="admin-stat-card">

                <span class="stat-icon">
                    🎫
                </span>

                <div>

                    <p>Total Bookings</p>

                    <h2>0</h2>

                </div>

            </div>


            <div class="admin-stat-card">

                <span class="stat-icon">
                    ✅
                </span>

                <div>

                    <p>Confirmed</p>

                    <h2>0</h2>

                </div>

            </div>


            <div class="admin-stat-card">

                <span class="stat-icon">
                    ⏳
                </span>

                <div>

                    <p>Pending</p>

                    <h2>0</h2>

                </div>

            </div>


            <div class="admin-stat-card">

                <span class="stat-icon">
                    ❌
                </span>

                <div>

                    <p>Cancelled</p>

                    <h2>0</h2>

                </div>

            </div>

        </section>


        <!-- BOOKING TABLE -->

        <section class="admin-section">

            <div class="student-toolbar">

                <div>

                    <h2>
                        Booking Records
                    </h2>

                    <p>
                        View all student reservations
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search booking..."
                    >

                </div>

            </div>


            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Student</th>

                            <th>Student ID</th>

                            <th>Route</th>

                            <th>Bus</th>

                            <th>Seat</th>

                            <th>Trip Date</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="9" class="no-data">

                                <div>
                                    🎫
                                </div>

                                <strong>
                                    No bookings found
                                </strong>

                                <p>
                                    Student booking records will appear here.
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