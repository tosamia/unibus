<?php

session_start();

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| DRIVER LOGIN CHECK
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["user_id"]) ||
    ($_SESSION["role"] ?? "") !== "driver"
) {

    header("Location: ../login.php");
    exit;

}

$driver_id = $_SESSION["user_id"];

$driver_name = $_SESSION["name"] ?? "Driver";


/*
|--------------------------------------------------------------------------
| GET DRIVER'S UPCOMING TRIP
|--------------------------------------------------------------------------
*/

$sql = "

    SELECT

        s.id AS schedule_id,

        s.departure_time,
        s.arrival_time,
        s.travel_date,
        s.status,

        b.id AS bus_id,
        b.bus_number,
        b.bus_name,
        b.total_seats,
        b.status AS bus_status,

        r.id AS route_id,
        r.route_name,
        r.start_point,
        r.end_point

    FROM schedules s

    INNER JOIN buses b
        ON s.bus_id = b.id

    INNER JOIN routes r
        ON s.route_id = r.id

    WHERE s.driver_id = ?

      AND s.travel_date >= CURDATE()

      AND s.status = 'scheduled'

    ORDER BY

        s.travel_date ASC,
        s.departure_time ASC

    LIMIT 1

";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die(
        "Trip query failed: " .
        $conn->error
    );

}

$stmt->bind_param(
    "i",
    $driver_id
);

$stmt->execute();

$result = $stmt->get_result();

$trip = null;

if ($result->num_rows > 0) {

    $trip = $result->fetch_assoc();

}

$stmt->close();



/*
|--------------------------------------------------------------------------
| TODAY'S BOOKINGS
|--------------------------------------------------------------------------
|
| bookings
|     ↓
| schedule_id
|     ↓
| schedules
|     ↓
| bus_id
|
| We count confirmed bookings for the
| driver's assigned schedule.
|
|--------------------------------------------------------------------------
*/

$today_bookings = 0;


if ($trip) {

    $booking_sql = "

        SELECT COUNT(*) AS total

        FROM bookings bk

        INNER JOIN schedules s

            ON bk.schedule_id = s.id

        WHERE s.id = ?

          AND bk.status = 'confirmed'

          AND s.travel_date = CURDATE()

    ";


    $booking_stmt =
        $conn->prepare($booking_sql);


    if (!$booking_stmt) {

        die(
            "Booking query failed: " .
            $conn->error
        );

    }


    $booking_stmt->bind_param(
        "i",
        $trip["schedule_id"]
    );


    $booking_stmt->execute();


    $booking_result =
        $booking_stmt->get_result();


    $booking_data =
        $booking_result->fetch_assoc();


    if ($booking_data) {

        $today_bookings =
            (int) $booking_data["total"];

    }


    $booking_stmt->close();

}


/*
|--------------------------------------------------------------------------
| GET BOOKED SEATS
|--------------------------------------------------------------------------
|
| This gets the actual seat numbers booked
| for the driver's current schedule.
|
|--------------------------------------------------------------------------
*/

$booked_seats = [];


if ($trip) {

    $seat_sql = "

        SELECT seat_number

        FROM bookings

        WHERE schedule_id = ?

          AND status = 'confirmed'

        ORDER BY seat_number ASC

    ";


    $seat_stmt =
        $conn->prepare($seat_sql);


    if (!$seat_stmt) {

        die(
            "Seat query failed: " .
            $conn->error
        );

    }


    $seat_stmt->bind_param(
        "i",
        $trip["schedule_id"]
    );


    $seat_stmt->execute();


    $seat_result =
        $seat_stmt->get_result();


    while (
        $seat_row =
        $seat_result->fetch_assoc()
    ) {

        $booked_seats[] =
            $seat_row["seat_number"];

    }


    $seat_stmt->close();

}


/*
|--------------------------------------------------------------------------
| CALCULATE AVAILABLE SEATS
|--------------------------------------------------------------------------
*/

$booked_count = count($booked_seats);

$available_seats = 0;


if ($trip) {

    $available_seats =
        max(
            0,
            (int) $trip["total_seats"]
            - $booked_count
        );

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
        Driver Dashboard | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        .booking-info {
            margin-top: 20px;

            padding: 18px;

            background: #f8fafc;

            border: 1px solid #e4e7ec;

            border-radius: 12px;
        }


        .booking-info-title {
            font-size: 15px;

            font-weight: 700;

            color: #172033;

            margin-bottom: 12px;
        }


        .seat-list {
            display: flex;

            flex-wrap: wrap;

            gap: 8px;
        }


        .seat-badge {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 42px;

            padding: 7px 10px;

            background: #eaf2ff;

            color: #1769e0;

            border-radius: 8px;

            font-size: 13px;

            font-weight: 700;
        }


        .no-seat {
            color: #98a2b3;

            font-size: 14px;
        }


        .capacity-info {
            margin-top: 15px;

            display: flex;

            gap: 20px;

            flex-wrap: wrap;
        }


        .capacity-item {
            font-size: 13px;

            color: #667085;
        }


        .capacity-item strong {
            color: #172033;
        }

    </style>

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


            <a
                href="driver-dashboard.php"
                class="active"
            >

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


            <a href="notices.php">

                📢 Notices

            </a>


        </nav>


        <a
            href="../logout.php"
            class="driver-logout"
        >

            ↪ Logout

        </a>


    </aside>


    <!-- MAIN -->

    <main class="driver-main">


        <!-- HEADER -->

        <header class="driver-header">


            <div>

                <h1>

                    Driver Dashboard

                </h1>


                <p>

                    Welcome back,
                    <?= htmlspecialchars($driver_name) ?>!

                </p>

            </div>


            <div class="driver-profile">

                🧑

                <?= htmlspecialchars($driver_name) ?>

            </div>


        </header>



        <?php if ($trip): ?>


        <!-- UPCOMING TRIP -->

        <section class="driver-trip-card">


            <div class="trip-title">


                <div>

                    <span class="trip-icon">

                        🚌

                    </span>


                    <div>

                        <h2>

                            Upcoming Trip

                        </h2>


                        <p>

                            Your assigned bus trip

                        </p>

                    </div>

                </div>


                <span class="trip-status">

                    <?= htmlspecialchars(
                        ucfirst($trip["status"])
                    ) ?>

                </span>


            </div>



            <!-- TRIP DETAILS -->

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

                        if (
                            !empty(
                                $trip["arrival_time"]
                            )
                        ) {

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

                        Bus Capacity

                    </span>


                    <strong>

                        <?= htmlspecialchars(
                            $trip["total_seats"]
                        ) ?>

                        Seats

                    </strong>

                </div>


            </div>



            <!-- BOOKING INFORMATION -->

            <div class="booking-info">


                <div class="booking-info-title">

                    🎫 Today's Confirmed Bookings

                </div>


                <div class="seat-list">


                    <?php if (
                        count($booked_seats) > 0
                    ): ?>


                        <?php foreach (
                            $booked_seats
                            as $seat
                        ): ?>


                            <span class="seat-badge">

                                <?= htmlspecialchars(
                                    $seat
                                ) ?>

                            </span>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <span class="no-seat">

                            No confirmed bookings yet.

                        </span>


                    <?php endif; ?>


                </div>


                <div class="capacity-info">


                    <div class="capacity-item">

                        Total Seats:

                        <strong>

                            <?= htmlspecialchars(
                                $trip["total_seats"]
                            ) ?>

                        </strong>

                    </div>


                    <div class="capacity-item">

                        Booked:

                        <strong>

                            <?= $booked_count ?>

                        </strong>

                    </div>


                    <div class="capacity-item">

                        Available:

                        <strong>

                            <?= $available_seats ?>

                        </strong>

                    </div>


                </div>


            </div>



            <!-- ACTION BUTTONS -->

            <div class="trip-actions">


                <a href="my-trip.php">

                    🛣️ View Trip

                </a>


                <a href="trip-status.php">

                    🔄 Update Status

                </a>


            </div>


        </section>


        <?php else: ?>


        <!-- NO TRIP -->

        <section class="driver-section">


            <div class="admin-empty">


                <div>

                    🚌

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



        <!-- DRIVER STATISTICS -->

        <section class="driver-stats">


            <!-- BUS -->

            <div class="driver-stat-card">


                <span>

                    🚌

                </span>


                <div>

                    <p>

                        Assigned Bus

                    </p>


                    <h2>

                        <?php if ($trip): ?>

                            <?= htmlspecialchars(
                                $trip["bus_number"]
                            ) ?>

                        <?php else: ?>

                            None

                        <?php endif; ?>

                    </h2>

                </div>


            </div>



            <!-- ROUTE -->

            <div class="driver-stat-card">


                <span>

                    🛣️

                </span>


                <div>

                    <p>

                        Assigned Route

                    </p>


                    <h2>

                        <?php if ($trip): ?>

                            <?= htmlspecialchars(
                                $trip["route_name"]
                            ) ?>

                        <?php else: ?>

                            None

                        <?php endif; ?>

                    </h2>

                </div>


            </div>



            <!-- BOOKINGS -->

            <div class="driver-stat-card">


                <span>

                    🎫

                </span>


                <div>

                    <p>

                        Today's Bookings

                    </p>


                    <h2>

                        <?= htmlspecialchars(
                            $today_bookings
                        ) ?>

                    </h2>

                </div>


            </div>


        </section>



        <!-- NOTICE -->

        <section class="driver-notice">


            <div class="notice-icon">

                📢

            </div>


            <div>

                <h3>

                    Stay Updated

                </h3>


                <p>

                    Check the Notices page regularly
                    for important announcements from
                    the administration.

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