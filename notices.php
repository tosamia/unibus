<?php

require_once "config/database.php";

$sql = "SELECT id, title, message, created_at
        FROM notices
        ORDER BY created_at DESC";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Notices | UniBus</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notices.css">

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        🚌 UniBus
    </div>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="about.php">
            About
        </a>

        <a href="schedule.php">
            Bus Schedule
        </a>

        <a href="notices.php" class="active">
            Notices
        </a>

        <a href="contact.php">
            Contact
        </a>

    </div>

    <div class="nav-buttons">

        <a href="login.php" class="login-btn">
            Login
        </a>

        <a href="register.php" class="register-btn">
            Register
        </a>

    </div>

</nav>


<!-- PAGE HEADER -->

<section class="notices-header">

    <span class="section-label">
        NOTICES & UPDATES
    </span>

    <h1>
        Latest Bus Notices
    </h1>

    <p>
        Stay updated with important announcements,
        schedule changes, and transportation information.
    </p>

</section>


<!-- NOTICES -->

<section class="notices-section">

    <div class="notices-container">


        <?php if ($result && $result->num_rows > 0): ?>


            <?php while ($notice = $result->fetch_assoc()): ?>

                <div class="notice-card">

                    <div class="notice-icon">
                        📢
                    </div>

                    <div class="notice-content">

                        <div class="notice-top">

                            <span class="notice-category important">
                                Notice
                            </span>

                            <span class="notice-date">

                                <?= date(
                                    "F d, Y",
                                    strtotime($notice["created_at"])
                                ) ?>

                            </span>

                        </div>


                        <h2>

                            <?= htmlspecialchars(
                                $notice["title"]
                            ) ?>

                        </h2>


                        <p>

                            <?= nl2br(
                                htmlspecialchars(
                                    $notice["message"]
                                )
                            ) ?>

                        </p>


                    </div>

                </div>

            <?php endwhile; ?>


        <?php else: ?>


            <div class="notice-card">

                <div class="notice-icon">
                    📢
                </div>

                <div class="notice-content">

                    <h2>
                        No Notices Available
                    </h2>

                    <p>
                        There are currently no announcements.
                        Please check again later.
                    </p>

                </div>

            </div>


        <?php endif; ?>


    </div>

</section>


<!-- FOOTER -->

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

            <a href="index.php">
                Home
            </a>

            <a href="about.php">
                About
            </a>

            <a href="schedule.php">
                Bus Schedule
            </a>

            <a href="notices.php">
                Notices
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

<?php

$conn->close();

?>