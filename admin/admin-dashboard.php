<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | UniBus</title>

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

            <a href="admin-dashboard.php" class="active">
                📊 Dashboard
            </a>

            <a href="students.php">
                👨‍🎓 Students
            </a>

            <a href="drivers.php">
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
                <h1>Admin Dashboard</h1>

                <p>
                    Welcome back, Admin!
                </p>
            </div>

            <div class="admin-profile">
                👨‍💼 Admin
            </div>

        </header>


        <!-- STAT CARDS -->

        <section class="admin-stats">

            <div class="admin-stat-card">

                <span class="stat-icon">
                    👨‍🎓
                </span>

                <div>
                    <p>Total Students</p>
                    <h2>0</h2>
                </div>

            </div>


            <div class="admin-stat-card">

                <span class="stat-icon">
                    🚌
                </span>

                <div>
                    <p>Total Buses</p>
                    <h2>0</h2>
                </div>

            </div>


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
                    🧑
                </span>

                <div>
                    <p>Total Drivers</p>
                    <h2>0</h2>
                </div>

            </div>

        </section>


        <!-- QUICK ACTIONS -->

        <section class="admin-section">

            <div class="section-heading">

                <h2>Quick Actions</h2>

                <p>
                    Manage the UniBus system
                </p>

            </div>


            <div class="admin-actions">

                <a href="buses.php" class="admin-action">
                    🚌
                    <span>Manage Buses</span>
                </a>

                <a href="routes.php" class="admin-action">
                    🛣️
                    <span>Manage Routes</span>
                </a>

                <a href="schedules.php" class="admin-action">
                    🕐
                    <span>Manage Schedules</span>
                </a>

                <a href="notices.php" class="admin-action">
                    📢
                    <span>Post Notice</span>
                </a>

            </div>

        </section>


        <!-- RECENT BOOKINGS -->

        <section class="admin-section">

            <div class="section-heading">

                <h2>Recent Bookings</h2>

                <a href="bookings.php">
                    View All
                </a>

            </div>


            <div class="admin-empty">

                <div>
                    🎫
                </div>

                <h3>No bookings yet</h3>

                <p>
                    Booking records will appear here.
                </p>

            </div>

        </section>

    </main>

</div>

</body>

</html>