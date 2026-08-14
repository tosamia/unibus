<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "driver") {
    header("Location: ../login.php");
    exit;
}

$driver_id = $_SESSION["user_id"];
$driver_name = $_SESSION["name"] ?? "Driver";

$sql = "
    SELECT
        b.id,
        b.bus_number,
        b.bus_name,
        b.total_seats,
        b.status,

        r.route_name,
        r.start_point,
        r.end_point

    FROM schedules s

    INNER JOIN buses b
        ON s.bus_id = b.id

    INNER JOIN routes r
        ON s.route_id = r.id

    WHERE s.driver_id = ?

    ORDER BY
        s.travel_date ASC,
        s.departure_time ASC

    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driver_id);
$stmt->execute();

$result = $stmt->get_result();

$bus = null;

if ($result->num_rows > 0) {
    $bus = $result->fetch_assoc();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Bus | Driver | UniBus</title>

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

            <a href="bus-info.php"
               class="active">
                🚌 My Bus
            </a>

            <a href="trip-status.php">
                🔄 Trip Status
            </a>

            <a href="../notices.php">
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
                    My Bus
                </h1>

                <p>
                    View your assigned bus information
                </p>

            </div>

            <div class="driver-profile">

                🧑
                <?= htmlspecialchars($driver_name) ?>

            </div>

        </header>


        <?php if ($bus): ?>


            <!-- BUS PROFILE -->

            <section class="driver-section bus-profile-card">

                <div class="bus-image">
                    🚌
                </div>

                <div class="bus-profile-content">

                    <span class="bus-label">
                        Assigned Bus
                    </span>

                    <h2>

                        <?= htmlspecialchars(
                            $bus["bus_number"]
                        ) ?>

                    </h2>

                    <p>

                        <?= htmlspecialchars(
                            $bus["bus_name"] ?? "University Bus"
                        ) ?>

                    </p>

                    <span class="bus-status">

                        ●
                        <?= htmlspecialchars(
                            ucfirst($bus["status"])
                        ) ?>

                    </span>

                </div>

            </section>


            <!-- BUS DETAILS -->

            <section class="driver-section">

                <div class="driver-section-title">

                    <h2>
                        Bus Information
                    </h2>

                    <p>
                        Details of your assigned vehicle
                    </p>

                </div>


                <div class="trip-info-grid">


                    <div class="trip-info-box">

                        <span>
                            🚌 Bus Number
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $bus["bus_number"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            🏷️ Bus Name
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $bus["bus_name"] ?? "Not specified"
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            💺 Total Capacity
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $bus["total_seats"]
                            ) ?>

                            Seats

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            🟢 Bus Status
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                ucfirst($bus["status"])
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            🛣️ Assigned Route
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $bus["route_name"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            📍 Route
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $bus["start_point"]
                            ) ?>

                            →

                            <?= htmlspecialchars(
                                $bus["end_point"]
                            ) ?>

                        </strong>

                    </div>

                </div>

            </section>


            <!-- INFORMATION NOTICE -->

            <section class="driver-notice">

                <div class="notice-icon">
                    🚌
                </div>

                <div>

                    <h3>
                        Vehicle Information
                    </h3>

                    <p>
                        If there is any problem with your
                        assigned bus, please contact the
                        administrator.
                    </p>

                </div>

            </section>


        <?php else: ?>


            <!-- NO BUS -->

            <section class="driver-section">

                <div class="admin-empty">

                    <div>
                        🚌
                    </div>

                    <h3>
                        No Bus Assigned
                    </h3>

                    <p>
                        You currently have no bus assigned
                        to your account.
                    </p>

                </div>

            </section>


        <?php endif; ?>


    </main>

</div>

</body>

</html>

<?php

$conn->close();

?>