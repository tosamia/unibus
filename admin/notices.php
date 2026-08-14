<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notices | Admin | UniBus</title>

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

            <a href="bookings.php">
                🎫 Bookings
            </a>

            <a href="notices.php" class="active">
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

                <h1>Notices</h1>

                <p>
                    Manage announcements and bus service notices
                </p>

            </div>

            <div class="admin-profile">
                👨‍💼 Admin
            </div>

        </header>


        <!-- NOTICE MANAGEMENT -->

        <section class="admin-section">

            <div class="student-toolbar">

                <div>

                    <h2>
                        Notice Board
                    </h2>

                    <p>
                        Publish important information for students and drivers
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search notice..."
                    >

                    <button type="button">
                        + Add Notice
                    </button>

                </div>

            </div>


            <!-- NOTICE TABLE -->

            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Type</th>

                            <th>Notice Date</th>

                            <th>Off-Day Date</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="7" class="no-data">

                                <div>
                                    📢
                                </div>

                                <strong>
                                    No notices found
                                </strong>

                                <p>
                                    Announcements and off-day notices will appear here.
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- NOTICE TYPES -->

        <section class="admin-section">

            <div class="section-heading">

                <div>

                    <h2>
                        Notice Types
                    </h2>

                    <p>
                        Different types of announcements the admin can publish.
                    </p>

                </div>

            </div>


            <div class="admin-actions">

                <div class="admin-action">

                    📢

                    <span>
                        General Notice
                    </span>

                </div>


                <div class="admin-action">

                    📅

                    <span>
                        Off-Day / Holiday
                    </span>

                </div>


                <div class="admin-action">

                    🚌

                    <span>
                        Bus Service Update
                    </span>

                </div>


                <div class="admin-action">

                    ⚠️

                    <span>
                        Important Notice
                    </span>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>