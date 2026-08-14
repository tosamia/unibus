<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "driver") {
    header("Location: ../login.php");
    exit;
}

$driver_name = $_SESSION["name"] ?? "Driver";

$sql = "
    SELECT
        id,
        title,
        message,
        created_at
    FROM notices
    ORDER BY created_at DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Notices | Driver | UniBus</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<div class="driver-layout">


    <!-- SIDEBAR -->

    <aside class="driver-sidebar">

        <div class="driver-logo">
            🚌 UniBus
        </div>

        <p class="driver-role">
            Driver Panel
        </p>

        <nav>

            <a href="driver-dashboard.php">
                📊 Dashboard
            </a>

            <a href="my-trip.php">
                🛣️ My Trip
            </a>

            <a href="bus-info.php">
                🚌 My Bus
            </a>

            <a href="trip-status.php">
                🔄 Trip Status
            </a>

            <a href="notices.php"
               class="active">
                📢 Notices
            </a>

        </nav>

        <a href="../login.php"
           class="driver-logout">

            ↪ Logout

        </a>

    </aside>


    <!-- MAIN -->

    <main class="driver-main">


        <!-- HEADER -->

        <header class="driver-header">

            <div>

                <h1>
                    Notices
                </h1>

                <p>
                    Important announcements for drivers
                </p>

            </div>

            <div class="driver-profile">

                🧑
                <?= htmlspecialchars($driver_name) ?>

            </div>

        </header>


        <!-- NOTICE LIST -->

        <section class="driver-section">


            <div class="driver-section-title">

                <h2>
                    Latest Notices
                </h2>

                <p>
                    Important information from the administration
                </p>

            </div>


            <div class="driver-notice-list">


                <?php if ($result && $result->num_rows > 0): ?>


                    <?php while ($notice = $result->fetch_assoc()): ?>


                        <article class="driver-notice-item">


                            <div class="notice-item-icon">
                                📢
                            </div>


                            <div class="notice-item-content">


                                <div class="notice-item-top">

                                    <h3>

                                        <?= htmlspecialchars(
                                            $notice["title"]
                                        ) ?>

                                    </h3>

                                    <span class="notice-badge general">
                                        Notice
                                    </span>

                                </div>


                                <p>

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $notice["message"]
                                        )
                                    ) ?>

                                </p>


                                <small>

                                    Date:

                                    <?= date(
                                        "d M Y, h:i A",
                                        strtotime(
                                            $notice["created_at"]
                                        )
                                    ) ?>

                                </small>


                            </div>

                        </article>


                    <?php endwhile; ?>


                <?php else: ?>


                    <!-- NO NOTICES -->

                    <article class="driver-notice-item">

                        <div class="notice-item-icon">
                            📢
                        </div>

                        <div class="notice-item-content">

                            <h3>
                                No Notices
                            </h3>

                            <p>
                                There are currently no announcements
                                from the administration.
                            </p>

                        </div>

                    </article>


                <?php endif; ?>


            </div>

        </section>


        <!-- INFORMATION -->

        <section class="driver-notice">

            <div class="notice-icon">
                ℹ️
            </div>

            <div>

                <h3>
                    Stay Updated
                </h3>

                <p>
                    Check this page regularly for important
                    university and bus service announcements.
                </p>

            </div>

        </section>


    </main>

</div>

</body>

</html>

<?php

$conn->close();

?>