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
        s.id,
        s.departure_time,
        s.arrival_time,
        s.travel_date,
        s.status,

        b.bus_number,
        b.bus_name,
        b.total_seats,

        r.route_name,
        r.start_point,
        r.end_point,
        r.stops

    FROM schedules s

    INNER JOIN buses b
        ON s.bus_id = b.id

    INNER JOIN routes r
        ON s.route_id = r.id

    WHERE s.driver_id = ?
    AND s.status = 'scheduled'

    ORDER BY
        s.travel_date ASC,
        s.departure_time ASC

    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driver_id);
$stmt->execute();

$result = $stmt->get_result();

$trip = null;

if ($result->num_rows > 0) {
    $trip = $result->fetch_assoc();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Trip | Driver | UniBus</title>

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

            <a href="my-trip.php"
               class="active">
                🛣️ My Trip
            </a>

            <a href="bus-info.php">
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


        <header class="driver-header">

            <div>

                <h1>
                    My Trip
                </h1>

                <p>
                    View your assigned bus trip
                </p>

            </div>

            <div class="driver-profile">

                🧑
                <?= htmlspecialchars($driver_name) ?>

            </div>

        </header>


        <?php if ($trip): ?>


            <!-- TRIP CARD -->

            <section class="driver-trip-card">


                <div class="trip-title">

                    <div>

                        <span class="trip-icon">
                            🚌
                        </span>

                        <div>

                            <h2>
                                <?= htmlspecialchars(
                                    $trip["route_name"]
                                ) ?>
                            </h2>

                            <p>
                                Your Assigned Trip
                            </p>

                        </div>

                    </div>


                    <span class="trip-status">

                        <?= htmlspecialchars(
                            ucfirst($trip["status"])
                        ) ?>

                    </span>

                </div>


                <!-- DETAILS -->

                <div class="trip-details">


                    <div>

                        <span>
                            Route
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["start_point"]
                            ) ?>

                            →

                            <?= htmlspecialchars(
                                $trip["end_point"]
                            ) ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Bus
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["bus_number"]
                            ) ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Departure
                        </span>

                        <strong>

                            <?= date(
                                "h:i A",
                                strtotime(
                                    $trip["departure_time"]
                                )
                            ) ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Arrival
                        </span>

                        <strong>

                            <?php

                            if (!empty(
                                $trip["arrival_time"]
                            )) {

                                echo date(
                                    "h:i A",
                                    strtotime(
                                        $trip["arrival_time"]
                                    )
                                );

                            } else {

                                echo "Not specified";

                            }

                            ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Travel Date
                        </span>

                        <strong>

                            <?= date(
                                "d M Y",
                                strtotime(
                                    $trip["travel_date"]
                                )
                            ) ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Capacity
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["total_seats"]
                            ) ?>

                            Seats

                        </strong>

                    </div>

                </div>


                <!-- ACTIONS -->

                <div class="trip-actions">

                    <a href="trip-status.php">
                        🔄 Update Status
                    </a>

                    <a href="bus-info.php">
                        🚌 View Bus
                    </a>

                </div>

            </section>


            <!-- ROUTE INFORMATION -->

            <section class="driver-section">


                <div class="driver-section-title">

                    <h2>
                        Route Information
                    </h2>

                    <p>
                        Your assigned route details
                    </p>

                </div>


                <div class="trip-info-grid">


                    <div class="trip-info-box">

                        <span>
                            🛣️ Route
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["route_name"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            📍 Start Point
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["start_point"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            🏁 End Point
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $trip["end_point"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="trip-info-box">

                        <span>
                            🚏 Stops
                        </span>

                        <strong>

                            <?= !empty($trip["stops"])
                                ? htmlspecialchars(
                                    $trip["stops"]
                                )
                                : "No stops specified"
                            ?>

                        </strong>

                    </div>

                </div>

            </section>


        <?php else: ?>


            <!-- NO TRIP -->

            <section class="driver-section">

                <div class="admin-empty">

                    <div>
                        🛣️
                    </div>

                    <h3>
                        No Trip Assigned
                    </h3>

                    <p>
                        You currently have no scheduled
                        trip assigned to you.
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