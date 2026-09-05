<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "driver") {
    header("Location: ../login.php");
    exit;
}

$driver_id = $_SESSION["user_id"];
$driver_name = $_SESSION["name"] ?? "Driver";

$message = "";
$error = "";


/*
|--------------------------------------------------------------------------
| Update Trip Status
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $schedule_id = intval($_POST["schedule_id"] ?? 0);
    $new_status = $_POST["status"] ?? "";

    $allowed_statuses = [
        "scheduled",
        "completed",
        "cancelled"
    ];

    if ($schedule_id <= 0) {

        $error = "Invalid trip.";

    } elseif (!in_array($new_status, $allowed_statuses, true)) {

        $error = "Invalid trip status.";

    } else {

        /*
        | Only update a schedule that belongs to
        | the logged-in driver.
        */

        $stmt = $conn->prepare(
            "UPDATE schedules
             SET status = ?
             WHERE id = ?
             AND driver_id = ?"
        );

        $stmt->bind_param(
            "sii",
            $new_status,
            $schedule_id,
            $driver_id
        );

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {

                $message = "Trip status updated successfully.";

            } else {

                $error = "Trip could not be updated.";

            }

        } else {

            $error = "Database error: " . $stmt->error;

        }

        $stmt->close();
    }
}


/*
|--------------------------------------------------------------------------
| Get Driver's Trip
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        s.id,
        s.departure_time,
        s.arrival_time,
        s.travel_date,
        s.status,

        b.bus_number,
        b.bus_name,

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

    <title>Trip Status | Driver | UniBus</title>

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

            <a href="trip-status.php"
               class="active">
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
                    Trip Status
                </h1>

                <p>
                    Manage the status of your assigned trip
                </p>

            </div>

            <div class="driver-profile">

                🧑
                <?= htmlspecialchars($driver_name) ?>

            </div>

        </header>


        <!-- SUCCESS -->

        <?php if ($message !== ""): ?>

            <section class="driver-notice">

                <div class="notice-icon">
                    ✅
                </div>

                <div>

                    <h3>
                        Success
                    </h3>

                    <p>
                        <?= htmlspecialchars($message) ?>
                    </p>

                </div>

            </section>

        <?php endif; ?>


        <!-- ERROR -->

        <?php if ($error !== ""): ?>

            <section class="driver-notice">

                <div class="notice-icon">
                    ⚠️
                </div>

                <div>

                    <h3>
                        Error
                    </h3>

                    <p>
                        <?= htmlspecialchars($error) ?>
                    </p>

                </div>

            </section>

        <?php endif; ?>


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
                                Assigned Bus Trip
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
                            Current Status
                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                ucfirst($trip["status"])
                            ) ?>

                        </strong>

                    </div>

                </div>


                <!-- STATUS FORM -->

                <div class="trip-actions">


                    <?php if ($trip["status"] === "scheduled"): ?>


                        <!-- COMPLETE -->

                        <form method="POST"
                              style="display:inline;">

                            <input
                                type="hidden"
                                name="schedule_id"
                                value="<?= $trip["id"] ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="completed"
                            >

                            <button type="submit">
                                ✅ Mark Completed
                            </button>

                        </form>


                        <!-- CANCEL -->

                        <form method="POST"
                              style="display:inline;">

                            <input
                                type="hidden"
                                name="schedule_id"
                                value="<?= $trip["id"] ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="cancelled"
                            >

                            <button type="submit">
                                ❌ Cancel Trip
                            </button>

                        </form>


                    <?php elseif ($trip["status"] === "completed"): ?>


                        <span>
                            ✅ This trip has been completed.
                        </span>


                    <?php elseif ($trip["status"] === "cancelled"): ?>


                        <span>
                            ❌ This trip has been cancelled.
                        </span>


                    <?php endif; ?>


                </div>

            </section>


        <?php else: ?>


            <!-- NO TRIP -->

            <section class="driver-section">

                <div class="admin-empty">

                    <div>
                        🔄
                    </div>

                    <h3>
                        No Trip Assigned
                    </h3>

                    <p>
                        You currently have no trip assigned
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