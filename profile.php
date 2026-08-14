<?php

session_start();

require_once "config/database.php";

/*
|--------------------------------------------------------------------------
| CHECK LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| GET LOGGED-IN USER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        student_id,
        department,
        email,
        role,
        created_at
    FROM users
    WHERE id = ?
");

if (!$stmt) {
    die("Profile query failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    session_destroy();

    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| USER DATA
|--------------------------------------------------------------------------
*/

$studentName = $user["name"];

$studentId = $user["student_id"] ?? "Not provided";

$department = $user["department"] ?? "Not provided";

$email = $user["email"];

$role = ucfirst($user["role"]);

$accountCreated = date(
    "M d, Y",
    strtotime($user["created_at"])
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Profile | UniBus</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <link
        rel="stylesheet"
        href="css/profile.css"
    >

</head>

<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <div class="logo">
        🚌 UniBus
    </div>


    <div class="nav-links">

        <a href="student-dashboard.php">
            Dashboard
        </a>

        <a href="schedule.php">
            Bus Schedule
        </a>

        <a href="my-bookings.php">
            My Bookings
        </a>

        <a href="contact.php">
            Contact
        </a>

    </div>


    <div class="nav-buttons">

        <a
            href="profile.php"
            class="profile-btn"
        >
            👤 Profile
        </a>

        <a
            href="logout.php"
            class="logout-btn"
        >
            Logout
        </a>

    </div>

</nav>



<!-- =========================
     PAGE HEADER
========================= -->

<section class="profile-header">

    <div class="profile-container">

        <span class="section-label">
            STUDENT PROFILE
        </span>

        <h1>
            My Profile
        </h1>

        <p>
            View your student information and account details.
        </p>

    </div>

</section>



<!-- =========================
     PROFILE SECTION
========================= -->

<section class="profile-section">

    <div class="profile-container">


        <!-- PROFILE CARD -->

        <div class="profile-card">


            <!-- PROFILE TOP -->

            <div class="profile-top">

                <div class="profile-avatar">
                    👤
                </div>


                <div class="profile-name">

                    <h2>
                        <?= htmlspecialchars($studentName); ?>
                    </h2>

                    <p>
                        Student ID:
                        <?= htmlspecialchars($studentId); ?>
                    </p>

                    <span class="student-status">
                        Active Student
                    </span>

                </div>

            </div>



            <!-- PERSONAL INFORMATION -->

            <div class="profile-block">

                <div class="block-heading">

                    <div>

                        <h2>
                            Personal Information
                        </h2>

                        <p>
                            Your basic student information
                        </p>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <span>
                            Full Name
                        </span>

                        <strong>
                            <?= htmlspecialchars($studentName); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Student ID
                        </span>

                        <strong>
                            <?= htmlspecialchars($studentId); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Email Address
                        </span>

                        <strong>
                            <?= htmlspecialchars($email); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Department
                        </span>

                        <strong>
                            <?= htmlspecialchars($department); ?>
                        </strong>

                    </div>

                </div>

            </div>



            <!-- ACADEMIC INFORMATION -->

            <div class="profile-block">

                <div class="block-heading">

                    <div>

                        <h2>
                            Academic Information
                        </h2>

                        <p>
                            Your university information
                        </p>

                    </div>

                </div>


                <div class="info-grid">


                    <div class="info-item">

                        <span>
                            Department
                        </span>

                        <strong>
                            <?= htmlspecialchars($department); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Student ID
                        </span>

                        <strong>
                            <?= htmlspecialchars($studentId); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Institution
                        </span>

                        <strong>
                            Sylhet Engineering College
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Account Role
                        </span>

                        <strong>
                            <?= htmlspecialchars($role); ?>
                        </strong>

                    </div>

                </div>

            </div>



            <!-- ACCOUNT INFORMATION -->

            <div class="profile-block">

                <div class="block-heading">

                    <div>

                        <h2>
                            Account Information
                        </h2>

                        <p>
                            Your UniBus account details
                        </p>

                    </div>

                </div>


                <div class="account-row">


                    <div>

                        <span>
                            Account Status
                        </span>

                        <strong class="active-account">
                            ● Active
                        </strong>

                    </div>


                    <div>

                        <span>
                            Account Type
                        </span>

                        <strong>
                            <?= htmlspecialchars($role); ?>
                        </strong>

                    </div>


                    <div>

                        <span>
                            Account Created
                        </span>

                        <strong>
                            <?= htmlspecialchars($accountCreated); ?>
                        </strong>

                    </div>

                </div>

            </div>



            <!-- ACTIONS -->

            <div class="profile-actions">

                <a
                    href="student-dashboard.php"
                    class="back-btn"
                >
                    ← Back to Dashboard
                </a>


                <a
                    href="my-bookings.php"
                    class="bookings-btn"
                >
                    🎫 My Bookings
                </a>

            </div>


        </div>

    </div>

</section>



<!-- =========================
     FOOTER
========================= -->

<footer class="footer">

    <div class="footer-content">


        <div>

            <h3>
                🚌 UniBus
            </h3>

            <p>
                University Bus Booking and Management System
            </p>

        </div>


        <div>

            <h3>
                Quick Links
            </h3>

            <a href="student-dashboard.php">
                Dashboard
            </a>

            <a href="schedule.php">
                Bus Schedule
            </a>

            <a href="my-bookings.php">
                My Bookings
            </a>

        </div>


        <div>

            <h3>
                Contact Info
            </h3>

            <p>
                📞 +880 1712-345678
            </p>

            <p>
                ✉ info@unibus.sec.edu.bd
            </p>

            <p>
                📍 Sylhet Engineering College
            </p>

        </div>

    </div>


    <div class="footer-bottom">

        © 2026 UniBus. All rights reserved.

    </div>

</footer>


</body>

</html>