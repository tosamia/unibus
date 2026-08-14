<?php
session_start();

require_once "config/database.php";

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "student") {
    header("Location: login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Student Information
|--------------------------------------------------------------------------
*/

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT id, name, student_id, department, email, role
    FROM users
    WHERE id = ? AND role = 'student'
");

if (!$stmt) {
    die("Student query failed: " . $conn->error);
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

$name = $user["name"];
$student_id = $user["student_id"];
$department = $user["department"];
$email = $user["email"];


/*
|--------------------------------------------------------------------------
| GET UPCOMING TRIP
|--------------------------------------------------------------------------
|
| bookings
|     ↓
| schedules
|     ↓
| buses
|     ↓
| routes
|
*/

$upcoming_trip = null;

$stmt = $conn->prepare("
    SELECT
        b.id AS booking_id,
        b.seat_number,
        b.status AS booking_status,

        s.id AS schedule_id,
        s.departure_time,
        s.arrival_time,
        s.travel_date,
        s.status AS schedule_status,

        bu.id AS bus_id,
        bu.bus_number,
        bu.bus_name,

        r.id AS route_id,
        r.route_name,
        r.start_point,
        r.end_point

    FROM bookings b

    INNER JOIN schedules s
        ON b.schedule_id = s.id

    INNER JOIN buses bu
        ON s.bus_id = bu.id

    INNER JOIN routes r
        ON s.route_id = r.id

    WHERE b.user_id = ?
      AND b.status = 'confirmed'
      AND s.status = 'scheduled'
      AND s.travel_date >= CURDATE()

    ORDER BY
        s.travel_date ASC,
        s.departure_time ASC,
        b.id DESC

    LIMIT 1
");

if (!$stmt) {
    die("Upcoming trip query failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$trip_result = $stmt->get_result();

if ($trip_result->num_rows > 0) {
    $upcoming_trip = $trip_result->fetch_assoc();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #172033;
        }

        a {
            text-decoration: none;
        }

        /* =========================
           NAVBAR
        ========================= */

        .student-navbar {
            width: 100%;
            min-height: 72px;

            background: #ffffff;

            border-bottom: 1px solid #e6e9ef;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 10px 7%;
        }

        .student-logo {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 25px;
            font-weight: 700;

            color: #1769e0;
        }

        .student-logo img {
            width: 42px;
            height: 42px;

            object-fit: contain;

            border-radius: 8px;
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

            font-size: 15px;

            padding: 8px 4px;

            transition: 0.2s;
        }

        .student-nav-links a:hover,
        .student-nav-links a.active {
            color: #1769e0;
        }

        .student-user {
            display: flex;
            align-items: center;
            gap: 10px;

            color: #475467;

            font-size: 14px;
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
            color: #d92d20 !important;

            font-weight: 600;

            margin-left: 5px;
        }

        .logout-link:hover {
            color: #b42318 !important;
        }

        /* =========================
           MAIN
        ========================= */

        .dashboard-container {
            width: 86%;
            max-width: 1200px;

            margin: 0 auto;

            padding: 45px 0 60px;
        }

        /* =========================
           WELCOME
        ========================= */

        .welcome-section {
            margin-bottom: 35px;
        }

        .welcome-label {
            color: #1769e0;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: 2px;

            margin-bottom: 10px;
        }

        .welcome-section h1 {
            font-size: 38px;

            line-height: 1.2;

            color: #172033;

            margin-bottom: 8px;
        }

        .welcome-section h1 span {
            color: #1769e0;
        }

        .welcome-section p {
            color: #667085;

            font-size: 16px;
        }

        /* =========================
           QUICK ACTIONS
        ========================= */

        .section-heading {
            text-align: center;

            margin-bottom: 25px;
        }

        .section-heading h2 {
            font-size: 30px;

            color: #172033;

            margin-bottom: 6px;
        }

        .section-heading p {
            color: #667085;

            font-size: 15px;
        }

        .quick-actions {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 40px;
        }

        .action-card {
            background: #ffffff;

            border: 1px solid #e7eaf0;

            border-radius: 14px;

            padding: 25px;

            transition: 0.25s;

            box-shadow: 0 4px 15px rgba(16, 24, 40, 0.04);
        }

        .action-card:hover {
            transform: translateY(-4px);

            box-shadow: 0 10px 25px rgba(16, 24, 40, 0.09);

            border-color: #c9dbff;
        }

        .action-icon {
            width: 52px;
            height: 52px;

            border-radius: 12px;

            background: #edf4ff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;

            margin-bottom: 18px;
        }

        .action-card h3 {
            font-size: 18px;

            color: #172033;

            margin-bottom: 8px;
        }

        .action-card p {
            font-size: 14px;

            color: #667085;

            line-height: 1.5;

            margin-bottom: 17px;
        }

        .action-link {
            color: #1769e0;

            font-size: 14px;

            font-weight: 600;
        }

        /* =========================
           INFORMATION CARDS
        ========================= */

        .info-grid {
            display: grid;

            grid-template-columns: 2fr 1fr;

            gap: 22px;

            margin-bottom: 25px;
        }

        .dashboard-card {
            background: #ffffff;

            border: 1px solid #e7eaf0;

            border-radius: 14px;

            padding: 25px;

            box-shadow: 0 4px 15px rgba(16, 24, 40, 0.04);
        }

        .dashboard-card-header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 22px;
        }

        .dashboard-card-header h2 {
            font-size: 21px;

            color: #172033;
        }

        .dashboard-card-header a {
            color: #1769e0;

            font-size: 14px;

            font-weight: 600;
        }

        /* =========================
           UPCOMING TRIP
        ========================= */

        .trip-card {
            border: 1px solid #e8ebf0;

            border-radius: 12px;

            padding: 20px;

            background: #fafbfc;
        }

        .trip-top {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 18px;
        }

        .trip-top h3 {
            color: #172033;

            font-size: 18px;
        }

        .trip-route-name {
            color: #667085;

            font-size: 14px;

            margin-bottom: 18px;
        }

        .status-badge {
            background: #ecfdf3;

            color: #027a48;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }

        .trip-details {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 15px;
        }

        .trip-detail {
            display: flex;

            flex-direction: column;

            gap: 5px;
        }

        .trip-detail span {
            font-size: 12px;

            color: #98a2b3;
        }

        .trip-detail strong {
            color: #344054;

            font-size: 14px;
        }

        /* =========================
           PROFILE
        ========================= */

        .profile-info {
            display: flex;

            flex-direction: column;

            gap: 17px;
        }

        .profile-row {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding-bottom: 13px;

            border-bottom: 1px solid #edf0f4;
        }

        .profile-row:last-child {
            border-bottom: none;

            padding-bottom: 0;
        }

        .profile-row span {
            color: #98a2b3;

            font-size: 13px;
        }

        .profile-row strong {
            color: #344054;

            font-size: 13px;

            text-align: right;
        }

        /* =========================
           NOTICE
        ========================= */

        .notice-box {
            background: #ffffff;

            border: 1px solid #e7eaf0;

            border-radius: 14px;

            padding: 22px 25px;

            display: flex;

            align-items: center;

            gap: 18px;

            box-shadow: 0 4px 15px rgba(16, 24, 40, 0.04);
        }

        .notice-icon {
            width: 48px;
            height: 48px;

            border-radius: 12px;

            background: #fff7e8;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 23px;

            flex-shrink: 0;
        }

        .notice-box h3 {
            font-size: 17px;

            color: #172033;

            margin-bottom: 5px;
        }

        .notice-box p {
            color: #667085;

            font-size: 14px;

            line-height: 1.5;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .student-navbar {
                padding: 12px 4%;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .student-nav-links {
                gap: 15px;
            }

        }

        @media (max-width: 700px) {

            .student-navbar {
                height: auto;

                padding: 18px 5%;

                flex-wrap: wrap;

                gap: 18px;
            }

            .student-nav-links {
                order: 3;

                width: 100%;

                justify-content: center;

                gap: 18px;

                flex-wrap: wrap;
            }

            .student-user {
                margin-left: auto;
            }

            .dashboard-container {
                width: 90%;

                padding-top: 30px;
            }

            .welcome-section h1 {
                font-size: 30px;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .trip-details {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<nav class="student-navbar">

    <a href="index.php" class="student-logo">

        <img src="images/logo.png" alt="UniBus Logo">

        <span>UniBus</span>

    </a>


    <div class="student-nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="student-dashboard.php" class="active">
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
            <?= htmlspecialchars($name); ?>
        </span>

        <a href="logout.php" class="logout-link">
            Logout
        </a>

    </div>

</nav>



<!-- =========================================================
     MAIN DASHBOARD
========================================================= -->

<main class="dashboard-container">


    <!-- WELCOME -->

    <section class="welcome-section">

        <div class="welcome-label">
            STUDENT DASHBOARD
        </div>

        <h1>
            Welcome back,
            <span><?= htmlspecialchars($name); ?></span>! 👋
        </h1>

        <p>
            Manage your university bus bookings and check your upcoming trips.
        </p>

    </section>



    <!-- QUICK ACTIONS -->

    <section>

        <div class="section-heading">

            <h2>
                Quick Actions
            </h2>

            <p>
                What would you like to do?
            </p>

        </div>


        <div class="quick-actions">


            <div class="action-card">

                <div class="action-icon">
                    🎫
                </div>

                <h3>
                    Book a Seat
                </h3>

                <p>
                    Find a bus and reserve your seat.
                </p>

                <a href="schedule.php" class="action-link">
                    Book Now →
                </a>

            </div>



            <div class="action-card">

                <div class="action-icon">
                    📋
                </div>

                <h3>
                    My Bookings
                </h3>

                <p>
                    View your booking records and tickets.
                </p>

                <a href="my-bookings.php" class="action-link">
                    View Bookings →
                </a>

            </div>



            <div class="action-card">

                <div class="action-icon">
                    🚌
                </div>

                <h3>
                    Bus Schedule
                </h3>

                <p>
                    Check routes and departure times.
                </p>

                <a href="schedule.php" class="action-link">
                    View Schedule →
                </a>

            </div>



            <div class="action-card">

                <div class="action-icon">
                    👤
                </div>

                <h3>
                    My Profile
                </h3>

                <p>
                    View your student information.
                </p>

                <a href="profile.php" class="action-link">
                    View Profile →
                </a>

            </div>


        </div>

    </section>



    <!-- INFORMATION -->

    <section class="info-grid">


        <!-- =====================================================
             UPCOMING TRIP
        ====================================================== -->

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <h2>
                    Upcoming Trip
                </h2>

                <a href="my-bookings.php">
                    View All
                </a>

            </div>


            <?php if ($upcoming_trip): ?>

                <div class="trip-card">

                    <div class="trip-top">

                        <h3>
                            <?= htmlspecialchars(
                                $upcoming_trip["bus_number"]
                            ); ?>
                        </h3>

                        <span class="status-badge">
                            Confirmed
                        </span>

                    </div>


                    <p class="trip-route-name">

                        <?= htmlspecialchars(
                            $upcoming_trip["route_name"]
                        ); ?>

                    </p>


                    <div class="trip-details">


                        <!-- ROUTE -->

                        <div class="trip-detail">

                            <span>
                                Route
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $upcoming_trip["start_point"]
                                    . " - "
                                    . $upcoming_trip["end_point"]
                                ); ?>

                            </strong>

                        </div>



                        <!-- DEPARTURE -->

                        <div class="trip-detail">

                            <span>
                                Departure
                            </span>

                            <strong>

                                <?= date(
                                    "h:i A",
                                    strtotime(
                                        $upcoming_trip["departure_time"]
                                    )
                                ); ?>

                            </strong>

                        </div>



                        <!-- BUS -->

                        <div class="trip-detail">

                            <span>
                                Bus
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $upcoming_trip["bus_number"]
                                ); ?>

                            </strong>

                        </div>



                        <!-- SEAT -->

                        <div class="trip-detail">

                            <span>
                                Seat
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $upcoming_trip["seat_number"]
                                ); ?>

                            </strong>

                        </div>



                        <!-- DATE -->

                        <div class="trip-detail">

                            <span>
                                Date
                            </span>

                            <strong>

                                <?= date(
                                    "d M Y",
                                    strtotime(
                                        $upcoming_trip["travel_date"]
                                    )
                                ); ?>

                            </strong>

                        </div>



                        <!-- STATUS -->

                        <div class="trip-detail">

                            <span>
                                Status
                            </span>

                            <strong>
                                Confirmed
                            </strong>

                        </div>


                    </div>

                </div>


            <?php else: ?>

                <div class="trip-card">

                    <div class="trip-top">

                        <h3>
                            No upcoming trip
                        </h3>

                        <span class="status-badge">
                            Ready to Book
                        </span>

                    </div>


                    <div class="trip-details">

                        <div class="trip-detail">

                            <span>
                                Route
                            </span>

                            <strong>
                                —
                            </strong>

                        </div>


                        <div class="trip-detail">

                            <span>
                                Departure
                            </span>

                            <strong>
                                —
                            </strong>

                        </div>


                        <div class="trip-detail">

                            <span>
                                Bus
                            </span>

                            <strong>
                                —
                            </strong>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>



        <!-- =====================================================
             STUDENT INFORMATION
        ====================================================== -->

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <h2>
                    My Information
                </h2>

                <a href="profile.php">
                    Profile
                </a>

            </div>


            <div class="profile-info">


                <div class="profile-row">

                    <span>
                        Name
                    </span>

                    <strong>
                        <?= htmlspecialchars($name); ?>
                    </strong>

                </div>


                <div class="profile-row">

                    <span>
                        Student ID
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $student_id ?: "Not provided"
                        ); ?>
                    </strong>

                </div>


                <div class="profile-row">

                    <span>
                        Department
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $department ?: "Not provided"
                        ); ?>
                    </strong>

                </div>


                <div class="profile-row">

                    <span>
                        Email
                    </span>

                    <strong>
                        <?= htmlspecialchars($email); ?>
                    </strong>

                </div>


            </div>

        </div>


    </section>



    <!-- NOTICE -->

    <section class="notice-box">

        <div class="notice-icon">
            📢
        </div>


        <div>

            <h3>
                Important Notice
            </h3>

            <p>

                Check the UniBus notices page regularly for
                schedule changes, university holidays and
                transportation announcements.

            </p>

        </div>

    </section>


</main>


</body>

</html>