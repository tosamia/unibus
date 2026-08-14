<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students | Admin | UniBus</title>

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

            <a href="students.php" class="active">
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

                <h1>Students</h1>

                <p>
                    Manage registered students
                </p>

            </div>

            <div class="admin-profile">
                👨‍💼 Admin
            </div>

        </header>


        <!-- SEARCH / ACTION AREA -->

        <section class="admin-section">

            <div class="student-toolbar">

                <div>

                    <h2>
                        Student List
                    </h2>

                    <p>
                        View and manage student accounts
                    </p>

                </div>

                <div class="student-actions">

                    <input
                        type="search"
                        placeholder="Search student..."
                    >

                    <button type="button">
                        + Add Student
                    </button>

                </div>

            </div>


            <!-- STUDENT TABLE -->

            <div class="admin-table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Student Name</th>

                            <th>Email</th>

                            <th>Student ID</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td colspan="6" class="no-data">

                                <div>
                                    👨‍🎓
                                </div>

                                <strong>
                                    No students found
                                </strong>

                                <p>
                                    Registered students will appear here.
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