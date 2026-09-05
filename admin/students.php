<?php

session_start();

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| CHECK ADMIN LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {

    header("Location: admin-login.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| GET STUDENTS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        name,
        student_id,
        department,
        email,
        created_at
    FROM users
    WHERE role = 'student'
    ORDER BY id DESC
";

$result = $conn->query($sql);

if (!$result) {

    die("Student query error: " . $conn->error);

}


/*
|--------------------------------------------------------------------------
| COUNT STUDENTS
|--------------------------------------------------------------------------
*/

$count_sql = "
    SELECT COUNT(*) AS total
    FROM users
    WHERE role = 'student'
";

$count_result = $conn->query($count_sql);

$total_students = 0;

if ($count_result) {

    $count_row = $count_result->fetch_assoc();

    $total_students = $count_row["total"];

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Students | Admin | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<div class="admin-layout">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

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


            <a
                href="students.php"
                class="active"
            >

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


        <a
            href="admin-logout.php"
            class="admin-logout"
        >

            ↪ Logout

        </a>


    </aside>



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="admin-main">


        <!-- HEADER -->

        <header class="admin-header">


            <div>

                <h1>

                    Students

                </h1>


                <p>

                    Manage registered students

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 System Admin

            </div>


        </header>



        <!-- =================================================
             STUDENT STATISTICS
        ================================================== -->

        <section class="admin-stats">


            <div class="admin-stat-card">


                <span class="stat-icon">

                    👨‍🎓

                </span>


                <div>

                    <p>

                        Total Students

                    </p>


                    <h2>

                        <?= htmlspecialchars($total_students); ?>

                    </h2>

                </div>


            </div>


        </section>



        <!-- =================================================
             STUDENT LIST
        ================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">


                <div>

                    <h2>

                        Student List

                    </h2>


                    <p>

                        Registered students in the UniBus system

                    </p>

                </div>


            </div>



            <!-- STUDENT TABLE -->

            <div class="admin-table-container">


                <table class="admin-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>


                            <th>
                                Student Name
                            </th>


                            <th>
                                Student ID
                            </th>


                            <th>
                                Department
                            </th>


                            <th>
                                Email
                            </th>


                            <th>
                                Joined
                            </th>


                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php if ($result->num_rows === 0): ?>


                        <tr>


                            <td
                                colspan="7"
                                class="no-data"
                            >


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


                    <?php else: ?>


                        <?php while ($student = $result->fetch_assoc()): ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <?= htmlspecialchars(
                                        $student["id"]
                                    ); ?>

                                </td>



                                <!-- NAME -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $student["name"]
                                        ); ?>

                                    </strong>

                                </td>



                                <!-- STUDENT ID -->

                                <td>

                                    <?= !empty($student["student_id"])
                                        ? htmlspecialchars(
                                            $student["student_id"]
                                        )
                                        : "-";
                                    ?>

                                </td>



                                <!-- DEPARTMENT -->

                                <td>

                                    <?= !empty($student["department"])
                                        ? htmlspecialchars(
                                            $student["department"]
                                        )
                                        : "-";
                                    ?>

                                </td>



                                <!-- EMAIL -->

                                <td>

                                    <?= htmlspecialchars(
                                        $student["email"]
                                    ); ?>

                                </td>



                                <!-- JOINED -->

                                <td>

                                    <?= htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $student["created_at"]
                                            )
                                        )
                                    ); ?>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <span class="status confirmed">

                                        ● Active

                                    </span>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </section>


    </main>


</div>


</body>

</html>

<?php

$conn->close();

?>