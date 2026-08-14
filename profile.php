<?php

// Sample student data
// Later these values will come from MySQL.

$studentName = "Samia Saifa";
$studentId = "SEC-12345";
$department = "Computer Science";
$email = "samia@example.com";
$phone = "+880 1712-345678";
$semester = "8th Semester";
$session = "2022-2023";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/profile.css">

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

        <a href="profile.php" class="profile-btn">
            👤 Profile
        </a>

        <a href="login.php" class="logout-btn">
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
                        <?php echo htmlspecialchars($studentName); ?>
                    </h2>

                    <p>
                        Student ID:
                        <?php echo htmlspecialchars($studentId); ?>
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
                            <?php echo htmlspecialchars($studentName); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Student ID
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($studentId); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Email Address
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($email); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Phone Number
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($phone); ?>
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
                            <?php echo htmlspecialchars($department); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Semester
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($semester); ?>
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Academic Session
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($session); ?>
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
                            Student
                        </strong>

                    </div>


                </div>

            </div>



            <!-- ACTIONS -->

            <div class="profile-actions">

                <a href="student-dashboard.php"
                   class="back-btn">

                    ← Back to Dashboard

                </a>


                <a href="my-bookings.php"
                   class="bookings-btn">

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