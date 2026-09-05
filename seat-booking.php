

<?php

session_start();

require_once "config/database.php";

/* =========================================================
   CHECK LOGIN
========================================================= */

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "student") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];


/* =========================================================
   GET SCHEDULE ID
========================================================= */

$schedule_id = isset($_GET["schedule_id"]) ? (int)$_GET["schedule_id"] : 0;

if ($schedule_id <= 0) {
    die("Invalid schedule.");
}


/* =========================================================
   GET SCHEDULE INFORMATION
========================================================= */

$stmt = $conn->prepare("
    SELECT
        id,
        bus_id,
        route_id,
        departure_time,
        arrival_time,
        travel_date,
        status
    FROM schedules
    WHERE id = ?
");

if (!$stmt) {
    die("Schedule query failed: " . $conn->error);
}

$stmt->bind_param("i", $schedule_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    die("Schedule not found.");
}

$schedule = $result->fetch_assoc();

$stmt->close();


/* =========================================================
   BASIC SCHEDULE INFORMATION
========================================================= */

$bus_name = "BUS-01";
$route_name = "City - SEC Campus";

$departure = date("h:i A", strtotime($schedule["departure_time"]));
$arrival   = date("h:i A", strtotime($schedule["arrival_time"]));

$travel_date = date(
    "F d, Y",
    strtotime($schedule["travel_date"])
);


/* =========================================================
   BOOKED SEATS
========================================================= */

$booked_seats = [];

$stmt = $conn->prepare("
    SELECT seat_number
    FROM bookings
    WHERE schedule_id = ?
    AND status = 'confirmed'
");

if (!$stmt) {
    die("Booking query failed: " . $conn->error);
}

$stmt->bind_param("i", $schedule_id);

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $booked_seats[] = $row["seat_number"];
}

$stmt->close();


/* =========================================================
   BOOK SEAT
========================================================= */

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $selected_seat = trim($_POST["seat_number"] ?? "");

    /* ---------------------------------------------
       CHECK SEAT
    --------------------------------------------- */

    if ($selected_seat === "") {

        $message = "Please select a seat.";
        $message_type = "error";

    } else {

        /* ---------------------------------------------
           CHECK IF SEAT ALREADY BOOKED
        --------------------------------------------- */

        $check = $conn->prepare("
            SELECT id
            FROM bookings
            WHERE schedule_id = ?
            AND seat_number = ?
            AND status = 'confirmed'
            LIMIT 1
        ");

        if (!$check) {

            die("Seat checking query failed: " . $conn->error);

        }

        $check->bind_param(
            "is",
            $schedule_id,
            $selected_seat
        );

        $check->execute();

        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {

            $message = "Sorry, this seat has already been booked.";
            $message_type = "error";

            $check->close();

        } else {

            $check->close();


            /* ---------------------------------------------
               INSERT BOOKING
            --------------------------------------------- */

            $insert = $conn->prepare("
                INSERT INTO bookings
                (
                    user_id,
                    schedule_id,
                    seat_number,
                    status
                )
                VALUES (?, ?, ?, 'confirmed')
            ");

            if (!$insert) {

                die("Booking insert failed: " . $conn->error);

            }

            $insert->bind_param(
                "iis",
                $user_id,
                $schedule_id,
                $selected_seat
            );

            if ($insert->execute()) {

                $booking_id = $insert->insert_id;

                $insert->close();

                /*
                 * Send the student to My Bookings
                 * after successful booking.
                 */

                header(
                    "Location: my-bookings.php?booking=success&id=" . $booking_id
                );

                exit;

            } else {

                $message = "Booking failed. Please try again.";
                $message_type = "error";

                $insert->close();

            }
        }
    }
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

    <title>Seat Booking | UniBus</title>


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f5f7fb;

            color: #172033;

        }


        a {
            text-decoration: none;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .student-navbar {

            width: 100%;

            min-height: 72px;

            background: #ffffff;

            border-bottom: 1px solid #e6e9ef;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 7%;

        }


        .student-logo {

            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 25px;

            font-weight: 700;

            color: #1769e0;

        }


        .student-logo span {
            color: #172033;
        }


        .student-nav-links {

            display: flex;

            align-items: center;

            gap: 28px;

        }


        .student-nav-links a {

            color: #475467;

            font-size: 14px;

            transition: 0.2s;

        }


        .student-nav-links a:hover {

            color: #1769e0;

        }


        .student-user {

            display: flex;

            align-items: center;

            gap: 10px;

            font-size: 14px;

            color: #475467;

        }


        .student-user-icon {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            background: #eaf2ff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 19px;

        }


        .logout-link {

            color: #d92d20;

            font-weight: 600;

            margin-left: 8px;

        }


        /* =====================================================
           PAGE
        ===================================================== */

        .page-container {

            width: 90%;

            max-width: 1150px;

            margin: auto;

            padding: 45px 0 60px;

        }


        .page-heading {

            margin-bottom: 30px;

        }


        .page-heading small {

            color: #1769e0;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 2px;

        }


        .page-heading h1 {

            margin-top: 8px;

            font-size: 36px;

            color: #172033;

        }


        .page-heading p {

            margin-top: 8px;

            color: #667085;

            font-size: 15px;

        }


        /* =====================================================
           MAIN GRID
        ===================================================== */

        .booking-layout {

            display: grid;

            grid-template-columns: 2fr 1fr;

            gap: 25px;

            align-items: start;

        }


        /* =====================================================
           BUS CARD
        ===================================================== */

        .bus-card {

            background: #ffffff;

            border: 1px solid #e6e9ef;

            border-radius: 16px;

            padding: 28px;

            box-shadow:
                0 5px 20px
                rgba(16, 24, 40, 0.05);

        }


        .bus-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

        }


        .bus-title {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .bus-icon {

            width: 48px;

            height: 48px;

            border-radius: 12px;

            background: #edf4ff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;

        }


        .bus-title h2 {

            font-size: 21px;

            margin-bottom: 4px;

        }


        .bus-title p {

            font-size: 13px;

            color: #667085;

        }


        .bus-date {

            text-align: right;

            color: #667085;

            font-size: 13px;

            line-height: 1.8;

        }


        .bus-date strong {

            color: #344054;

        }


        /* =====================================================
           FRONT
        ===================================================== */

        .front-label {

            width: 75%;

            margin: 0 auto 20px;

            padding: 10px;

            text-align: center;

            background: #f1f3f6;

            border-radius: 8px;

            color: #667085;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 1px;

        }


        /* =====================================================
           SEAT AREA
        ===================================================== */

        .seat-area {

            width: 75%;

            margin: auto;

        }


        .seat-row {

            display: grid;

            grid-template-columns: 1fr 1fr 30px 1fr 1fr;

            gap: 9px;

            margin-bottom: 10px;

            align-items: center;

        }


        .aisle {

            width: 100%;

        }


        .seat {

            height: 48px;

            border-radius: 9px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 13px;

            font-weight: 700;

            border: 2px solid transparent;

            cursor: pointer;

            transition: 0.2s;

        }


        /* =====================================================
           AVAILABLE = GREEN
        ===================================================== */

        .seat.available {

            background: #dcfce7;

            border-color: #22c55e;

            color: #15803d;

        }


        .seat.available:hover {

            background: #bbf7d0;

            transform: translateY(-2px);

        }


        /* =====================================================
           BOOKED = RED
        ===================================================== */

        .seat.booked {

            background: #fee2e2;

            border-color: #ef4444;

            color: #b91c1c;

            cursor: not-allowed;

            opacity: 0.95;

        }


        /* =====================================================
           SELECTED = BLUE
        ===================================================== */

        .seat.selected {

            background: #1769e0;

            border-color: #0d55bd;

            color: #ffffff;

        }


        /* =====================================================
           LEGEND
        ===================================================== */

        .legend {

            display: flex;

            justify-content: center;

            gap: 25px;

            margin-top: 28px;

            flex-wrap: wrap;

        }


        .legend-item {

            display: flex;

            align-items: center;

            gap: 7px;

            color: #667085;

            font-size: 13px;

        }


        .legend-box {

            width: 17px;

            height: 17px;

            border-radius: 4px;

        }


        .legend-available {

            background: #dcfce7;

            border: 1px solid #22c55e;

        }


        .legend-selected {

            background: #1769e0;

        }


        .legend-booked {

            background: #fee2e2;

            border: 1px solid #ef4444;

        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary-card {

            background: #ffffff;

            border: 1px solid #e6e9ef;

            border-radius: 16px;

            padding: 25px;

            box-shadow:
                0 5px 20px
                rgba(16, 24, 40, 0.05);

            position: sticky;

            top: 20px;

        }


        .summary-card h2 {

            font-size: 21px;

            margin-bottom: 20px;

        }


        .summary-row {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 13px 0;

            border-bottom: 1px solid #edf0f4;

            font-size: 14px;

        }


        .summary-row:last-of-type {

            border-bottom: none;

        }


        .summary-row span {

            color: #98a2b3;

        }


        .summary-row strong {

            color: #344054;

            text-align: right;

        }


        .selected-seat {

            color: #1769e0 !important;

            font-size: 17px;

        }


        .confirm-button {

            width: 100%;

            border: none;

            background: #1769e0;

            color: white;

            padding: 14px;

            border-radius: 9px;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            margin-top: 20px;

            transition: 0.2s;

        }


        .confirm-button:hover {

            background: #0d55bd;

        }


        .confirm-button:disabled {

            background: #cbd5e1;

            cursor: not-allowed;

        }


        /* =====================================================
           MESSAGE
        ===================================================== */

        .message {

            padding: 13px 16px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

        }


        .message.error {

            background: #fee2e2;

            color: #b91c1c;

            border: 1px solid #fecaca;

        }


        .message.success {

            background: #dcfce7;

            color: #15803d;

            border: 1px solid #bbf7d0;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {

            .student-navbar {

                padding: 18px 5%;

                flex-wrap: wrap;

                gap: 15px;

            }


            .student-nav-links {

                order: 3;

                width: 100%;

                justify-content: center;

                flex-wrap: wrap;

            }


            .booking-layout {

                grid-template-columns: 1fr;

            }


            .summary-card {

                position: static;

            }

        }


        @media (max-width: 600px) {

            .page-container {

                width: 94%;

                padding-top: 30px;

            }


            .page-heading h1 {

                font-size: 29px;

            }


            .bus-card {

                padding: 18px;

            }


            .bus-header {

                flex-direction: column;

                align-items: flex-start;

                gap: 12px;

            }


            .bus-date {

                text-align: left;

            }


            .seat-area,
            .front-label {

                width: 100%;

            }


            .seat-row {

                grid-template-columns:
                    1fr 1fr 15px 1fr 1fr;

                gap: 5px;

            }


            .seat {

                height: 42px;

                font-size: 11px;

            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<nav class="student-navbar">

    <a href="student-dashboard.php" class="student-logo">
        🚌 <span>UniBus</span>
    </a>


    <div class="student-nav-links">

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


    <div class="student-user">

        <div class="student-user-icon">
            👨‍🎓
        </div>

        <span>
            Student
        </span>

        <a
            href="logout.php"
            class="logout-link"
        >
            Logout
        </a>

    </div>

</nav>



<!-- =========================================================
     PAGE
========================================================= -->

<main class="page-container">


    <div class="page-heading">

        <small>
            SEAT BOOKING
        </small>

        <h1>
            Select Your Seat
        </h1>

        <p>
            Choose an available seat for your journey.
        </p>

    </div>



    <?php if ($message !== ""): ?>

        <div class="message <?= htmlspecialchars($message_type); ?>">

            <?= htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>



    <div class="booking-layout">


        <!-- =================================================
             BUS
        ================================================== -->

        <div class="bus-card">


            <div class="bus-header">


                <div class="bus-title">

                    <div class="bus-icon">
                        🚌
                    </div>

                    <div>

                        <h2>
                            <?= htmlspecialchars($bus_name); ?>
                        </h2>

                        <p>
                            <?= htmlspecialchars($route_name); ?>
                        </p>

                    </div>

                </div>


                <div class="bus-date">

                    📅
                    <strong>
                        <?= htmlspecialchars($travel_date); ?>
                    </strong>

                    <br>

                    🕐
                    <?= htmlspecialchars($departure); ?>

                </div>


            </div>



            <!-- FRONT -->

            <div class="front-label">

                🚍 FRONT

            </div>



            <!-- SEATS -->

            <form
                method="POST"
                id="bookingForm"
            >

                <input
                    type="hidden"
                    name="seat_number"
                    id="selectedSeat"
                    value=""
                >


                <div class="seat-area">


                    <!-- ROW A -->

                    <div class="seat-row">

                        <?php
                        $seats = ["A1", "A2", "A3", "A4"];
                        foreach ($seats as $seat):
                            $is_booked = in_array($seat, $booked_seats);
                        ?>

                            <div
                                class="seat <?= $is_booked ? 'booked' : 'available'; ?>"
                                data-seat="<?= $seat; ?>"
                            >
                                <?= $seat; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>



                    <!-- ROW B -->

                    <div class="seat-row">

                        <?php
                        $seats = ["B1", "B2", "B3", "B4"];
                        foreach ($seats as $seat):
                            $is_booked = in_array($seat, $booked_seats);
                        ?>

                            <div
                                class="seat <?= $is_booked ? 'booked' : 'available'; ?>"
                                data-seat="<?= $seat; ?>"
                            >
                                <?= $seat; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>



                    <!-- ROW C -->

                    <div class="seat-row">

                        <?php
                        $seats = ["C1", "C2", "C3", "C4"];
                        foreach ($seats as $seat):
                            $is_booked = in_array($seat, $booked_seats);
                        ?>

                            <div
                                class="seat <?= $is_booked ? 'booked' : 'available'; ?>"
                                data-seat="<?= $seat; ?>"
                            >
                                <?= $seat; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>



                    <!-- ROW D -->

                    <div class="seat-row">

                        <?php
                        $seats = ["D1", "D2", "D3", "D4"];
                        foreach ($seats as $seat):
                            $is_booked = in_array($seat, $booked_seats);
                        ?>

                            <div
                                class="seat <?= $is_booked ? 'booked' : 'available'; ?>"
                                data-seat="<?= $seat; ?>"
                            >
                                <?= $seat; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>



                    <!-- ROW E -->

                    <div class="seat-row">

                        <?php
                        $seats = ["E1", "E2", "E3", "E4"];
                        foreach ($seats as $seat):
                            $is_booked = in_array($seat, $booked_seats);
                        ?>

                            <div
                                class="seat <?= $is_booked ? 'booked' : 'available'; ?>"
                                data-seat="<?= $seat; ?>"
                            >
                                <?= $seat; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>


                </div>



                <!-- LEGEND -->

                <div class="legend">


                    <div class="legend-item">

                        <div class="legend-box legend-available"></div>

                        Available

                    </div>


                    <div class="legend-item">

                        <div class="legend-box legend-selected"></div>

                        Selected

                    </div>


                    <div class="legend-item">

                        <div class="legend-box legend-booked"></div>

                        Booked

                    </div>


                </div>


            </form>

        </div>



        <!-- =================================================
             BOOKING SUMMARY
        ================================================== -->

        <div class="summary-card">


            <h2>
                Booking Summary
            </h2>


            <div class="summary-row">

                <span>
                    Bus
                </span>

                <strong>
                    <?= htmlspecialchars($bus_name); ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Route
                </span>

                <strong>
                    <?= htmlspecialchars($route_name); ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    From
                </span>

                <strong>
                    City
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    To
                </span>

                <strong>
                    SEC Campus
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Date
                </span>

                <strong>
                    <?= htmlspecialchars($travel_date); ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Departure
                </span>

                <strong>
                    <?= htmlspecialchars($departure); ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Arrival
                </span>

                <strong>
                    <?= htmlspecialchars($arrival); ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Selected Seat
                </span>

                <strong
                    id="summarySeat"
                    class="selected-seat"
                >
                    None
                </strong>

            </div>



            <button
                type="submit"
                form="bookingForm"
                class="confirm-button"
                id="confirmButton"
                disabled
            >
                Confirm Booking
            </button>


        </div>


    </div>

</main>



<script>

/* =========================================================
   SEAT SELECTION
========================================================= */

const seats = document.querySelectorAll(".seat.available");

const selectedSeatInput =
    document.getElementById("selectedSeat");

const summarySeat =
    document.getElementById("summarySeat");

const confirmButton =
    document.getElementById("confirmButton");


seats.forEach(function(seat) {

    seat.addEventListener("click", function() {


        /* Remove previous selection */

        seats.forEach(function(item) {

            item.classList.remove("selected");

        });


        /* Select clicked seat */

        seat.classList.add("selected");


        const seatNumber =
            seat.getAttribute("data-seat");


        /* Put seat into hidden input */

        selectedSeatInput.value =
            seatNumber;


        /* Update summary */

        summarySeat.textContent =
            seatNumber;


        /* Enable confirm button */

        confirmButton.disabled = false;

    });

});


</script>


</body>

</html>
