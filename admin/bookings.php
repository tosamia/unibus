<?php

session_start();

require_once "../config/database.php";


/* =====================================================
   ADMIN LOGIN CHECK
===================================================== */

if (
    !isset($_SESSION["user_id"]) ||
    ($_SESSION["role"] ?? "") !== "admin"
) {
    header("Location: admin-login.php");
    exit;
}


/* =====================================================
   DELETE / CANCEL BOOKING
===================================================== */

if (isset($_GET["cancel"])) {

    $booking_id = (int) $_GET["cancel"];

    $sql = "UPDATE bookings
            SET status = 'cancelled'
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $stmt->close();

    }

    header("Location: bookings.php");
    exit;
}


/* =====================================================
   GET TOTAL BOOKINGS
===================================================== */

$count_sql = "SELECT COUNT(*) AS total
              FROM bookings";

$count_result = $conn->query($count_sql);

$total_bookings = 0;

if ($count_result) {

    $count_row = $count_result->fetch_assoc();

    $total_bookings = (int) $count_row["total"];
}


/* =====================================================
   CONFIRMED BOOKINGS
===================================================== */

$confirmed_sql = "SELECT COUNT(*) AS total
                  FROM bookings
                  WHERE status = 'confirmed'";

$confirmed_result = $conn->query($confirmed_sql);

$confirmed_bookings = 0;

if ($confirmed_result) {

    $confirmed_row = $confirmed_result->fetch_assoc();

    $confirmed_bookings =
        (int) $confirmed_row["total"];
}


/* =====================================================
   CANCELLED BOOKINGS
===================================================== */

$cancelled_sql = "SELECT COUNT(*) AS total
                  FROM bookings
                  WHERE status = 'cancelled'";

$cancelled_result = $conn->query($cancelled_sql);

$cancelled_bookings = 0;

if ($cancelled_result) {

    $cancelled_row = $cancelled_result->fetch_assoc();

    $cancelled_bookings =
        (int) $cancelled_row["total"];
}


/* =====================================================
   GET ALL BOOKINGS
===================================================== */

$sql = "

    SELECT

        bookings.id AS booking_id,
        bookings.seat_number,
        bookings.booking_date,
        bookings.status,

        users.name AS student_name,
        users.student_id,

        schedules.travel_date,
        schedules.departure_time,
        schedules.arrival_time,

        buses.bus_number,
        buses.bus_name,

        routes.route_name,
        routes.start_point,
        routes.end_point

    FROM bookings

    INNER JOIN users
        ON bookings.user_id = users.id

    INNER JOIN schedules
        ON bookings.schedule_id = schedules.id

    INNER JOIN buses
        ON schedules.bus_id = buses.id

    INNER JOIN routes
        ON schedules.route_id = routes.id

    ORDER BY bookings.id DESC
";


$result = $conn->query($sql);

if (!$result) {

    die(
        "Booking query error: " .
        $conn->error
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
        Bookings | Admin | UniBus
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        .booking-stats {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin-bottom: 25px;

        }


        .booking-stat-card {

            background: white;

            padding: 22px;

            border-radius: 12px;

            box-shadow:
                0 2px 10px
                rgba(0,0,0,0.06);

        }


        .booking-stat-card span {

            font-size: 28px;

        }


        .booking-stat-card p {

            margin: 10px 0 5px;

            color: #667085;

        }


        .booking-stat-card h2 {

            margin: 0;

        }


        .route-small {

            font-size: 13px;

            color: #777;

            margin-top: 4px;

        }


        .action-btn {

            color: #b42318;

            text-decoration: none;

            font-weight: 600;

        }


        .status {

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: 600;

        }


        .status.confirmed {

            background: #e8f7ed;

            color: #187a36;

        }


        .status.cancelled {

            background: #fdeaea;

            color: #b42318;

        }


        .seat-badge {

            display: inline-block;

            padding: 5px 9px;

            background: #eaf2ff;

            color: #1769e0;

            border-radius: 7px;

            font-weight: 700;

        }


        @media (max-width: 800px) {

            .booking-stats {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>


<body>


<div class="admin-layout">


    <!-- =================================================
         SIDEBAR
    ================================================== -->

    <aside class="admin-sidebar">

        <div class="admin-logo">

            🚌 UniBus

        </div>


        <p class="admin-role">

            Administration

        </p>


        <nav>

            <a href="admin-dashboard.php">

                📊 Dashboard

            </a>


            <a href="students.php">

                👨‍🎓 Students

            </a>


            <a href="drivers.php">

                🧑‍✈️ Drivers

            </a>


            <a href="buses.php">

                🚌 Buses

            </a>


            <a href="routes.php">

                🛣️ Routes

            </a>


            <a href="schedules.php">

                🕐 Schedules

            </a>


            <a
                href="bookings.php"
                class="active"
            >

                🎫 Bookings

            </a>


            <a href="notices.php">

                📢 Notices

            </a>

        </nav>


        <a
            href="admin-logout.php"
            class="admin-logout"
        >

            ↪ Logout

        </a>

    </aside>



    <!-- =================================================
         MAIN
    ================================================== -->

    <main class="admin-main">


        <!-- HEADER -->

        <header class="admin-header">

            <div>

                <h1>

                    Bookings

                </h1>

                <p>

                    View and manage student bus bookings

                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 Admin

            </div>

        </header>



        <!-- =================================================
             STATISTICS
        ================================================== -->

        <section class="booking-stats">


            <div class="booking-stat-card">

                <span>

                    🎫

                </span>

                <p>

                    Total Bookings

                </p>

                <h2>

                    <?php
                    echo $total_bookings;
                    ?>

                </h2>

            </div>


            <div class="booking-stat-card">

                <span>

                    ✅

                </span>

                <p>

                    Confirmed

                </p>

                <h2>

                    <?php
                    echo $confirmed_bookings;
                    ?>

                </h2>

            </div>


            <div class="booking-stat-card">

                <span>

                    ❌

                </span>

                <p>

                    Cancelled

                </p>

                <h2>

                    <?php
                    echo $cancelled_bookings;
                    ?>

                </h2>

            </div>


        </section>



        <!-- =================================================
             BOOKING LIST
        ================================================== -->

        <section class="admin-section">


            <div class="student-toolbar">

                <div>

                    <h2>

                        Booking Records

                    </h2>

                    <p>

                        View all student reservations

                    </p>

                </div>

            </div>



            <div class="admin-table-container">

                <table class="admin-table">


                    <thead>

                        <tr>

                            <th>

                                ID

                            </th>


                            <th>

                                Student

                            </th>


                            <th>

                                Student ID

                            </th>


                            <th>

                                Route

                            </th>


                            <th>

                                Bus

                            </th>


                            <th>

                                Seat

                            </th>


                            <th>

                                Trip Date

                            </th>


                            <th>

                                Status

                            </th>


                            <th>

                                Action

                            </th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php if ($result->num_rows === 0): ?>


                        <tr>

                            <td
                                colspan="9"
                                class="no-data"
                            >

                                <div>

                                    🎫

                                </div>


                                <strong>

                                    No bookings found

                                </strong>


                                <p>

                                    Student booking records
                                    will appear here.

                                </p>

                            </td>

                        </tr>


                    <?php else: ?>


                        <?php
                        while (
                            $booking =
                            $result->fetch_assoc()
                        ):
                        ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["booking_id"]
                                    );
                                    ?>

                                </td>



                                <!-- STUDENT -->

                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["student_name"]
                                        );
                                        ?>

                                    </strong>

                                </td>



                                <!-- STUDENT ID -->

                                <td>

                                    <?php

                                    echo !empty(
                                        $booking["student_id"]
                                    )
                                    ? htmlspecialchars(
                                        $booking["student_id"]
                                    )
                                    : "-";

                                    ?>

                                </td>



                                <!-- ROUTE -->

                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["route_name"]
                                        );
                                        ?>

                                    </strong>


                                    <div class="route-small">

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["start_point"]
                                        );
                                        ?>

                                        →

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["end_point"]
                                        );
                                        ?>

                                    </div>

                                </td>



                                <!-- BUS -->

                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["bus_number"]
                                        );
                                        ?>

                                    </strong>


                                    <?php
                                    if (
                                        !empty(
                                            $booking["bus_name"]
                                        )
                                    ):
                                    ?>

                                        <div
                                            class="route-small"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $booking["bus_name"]
                                            );
                                            ?>

                                        </div>

                                    <?php endif; ?>

                                </td>



                                <!-- SEAT -->

                                <td>

                                    <span
                                        class="seat-badge"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $booking["seat_number"]
                                        );
                                        ?>

                                    </span>

                                </td>



                                <!-- TRIP DATE -->

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        date(
                                            "M d, Y",
                                            strtotime(
                                                $booking["travel_date"]
                                            )
                                        )
                                    );
                                    ?>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <?php

                                    $status =
                                        $booking["status"];

                                    ?>

                                    <span
                                        class="status <?php
                                        echo $status === "confirmed"
                                            ? "confirmed"
                                            : "cancelled";
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            ucfirst($status)
                                        );
                                        ?>

                                    </span>

                                </td>



                                <!-- ACTION -->

                                <td>

                                    <?php
                                    if (
                                        $status === "confirmed"
                                    ):
                                    ?>

                                        <a
                                            href="bookings.php?cancel=<?php
                                            echo $booking["booking_id"];
                                            ?>"
                                            class="action-btn"
                                            onclick="return confirm('Are you sure you want to cancel this booking?');"
                                        >

                                            Cancel

                                        </a>

                                    <?php else: ?>

                                        <span>

                                            —

                                        </span>

                                    <?php endif; ?>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </section>


    </main>

</div>


</body>

</html>


<?php

$conn->close();

?>