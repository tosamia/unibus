<?php

session_start();

require_once "../config/database.php";

/* =====================================================
   CHECK ADMIN LOGIN
===================================================== */

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: admin-login.php");
    exit;
}


/* =====================================================
   ADD NOTICE
===================================================== */

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $notice_message = trim($_POST["message"] ?? "");

    if ($title === "" || $notice_message === "") {

        $message = "Please enter both title and message.";
        $message_type = "error";

    } else {

        $sql = "INSERT INTO notices (title, message)
                VALUES (?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            die("Notice query error: " . $conn->error);

        }

        $stmt->bind_param(
            "ss",
            $title,
            $notice_message
        );

        if ($stmt->execute()) {

            $message = "Notice published successfully!";
            $message_type = "success";

        } else {

            $message = "Failed to publish notice.";
            $message_type = "error";

        }

        $stmt->close();
    }
}


/* =====================================================
   DELETE NOTICE
===================================================== */

if (isset($_GET["delete"])) {

    $delete_id = (int) $_GET["delete"];

    if ($delete_id > 0) {

        $sql = "DELETE FROM notices WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "i",
                $delete_id
            );

            $stmt->execute();

            $stmt->close();

        }
    }

    header("Location: notices.php");
    exit;
}


/* =====================================================
   GET NOTICES
===================================================== */

$sql = "SELECT
            id,
            title,
            message,
            created_at
        FROM notices
        ORDER BY id DESC";

$result = $conn->query($sql);

if (!$result) {

    die("Notice fetch error: " . $conn->error);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Notices | Admin | UniBus</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>


<body>

<div class="admin-layout">


    <!-- =================================================
         SIDEBAR
    ================================================= -->

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

            <a href="bookings.php">
                🎫 Bookings
            </a>

            <a href="notices.php"
               class="active">
                📢 Notices
            </a>

        </nav>


        <a href="admin-logout.php"
           class="admin-logout">

            ↪ Logout

        </a>

    </aside>



    <!-- =================================================
         MAIN CONTENT
    ================================================= -->

    <main class="admin-main">


        <!-- HEADER -->

        <header class="admin-header">

            <div>

                <h1>
                    Notices
                </h1>

                <p>
                    Manage announcements and bus service notices
                </p>

            </div>


            <div class="admin-profile">

                👨‍💼 System Admin

            </div>

        </header>



        <!-- =================================================
             MESSAGE
        ================================================= -->

        <?php if ($message !== ""): ?>

            <div class="<?php echo htmlspecialchars($message_type); ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             ADD NOTICE FORM
        ================================================= -->

        <section class="admin-section">

            <div class="section-heading">

                <div>

                    <h2>
                        Publish New Notice
                    </h2>

                    <p>
                        Add an announcement for students and drivers.
                    </p>

                </div>

            </div>


            <form method="POST"
                  action="notices.php">


                <div>

                    <label for="title">
                        Notice Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Enter notice title"
                        required
                    >

                </div>


                <br>


                <div>

                    <label for="notice_message">
                        Message
                    </label>

                    <textarea
                        id="notice_message"
                        name="message"
                        rows="5"
                        placeholder="Write your notice..."
                        required
                    ></textarea>

                </div>


                <br>


                <button type="submit">

                    📢 Publish Notice

                </button>

            </form>

        </section>



        <!-- =================================================
             NOTICE LIST
        ================================================= -->

        <section class="admin-section">


            <div class="student-toolbar">

                <div>

                    <h2>
                        Notice Board
                    </h2>

                    <p>
                        Published announcements
                    </p>

                </div>


                <div class="student-actions">

                    <input
                        type="search"
                        id="noticeSearch"
                        placeholder="Search notice..."
                    >

                </div>

            </div>



            <!-- NOTICE TABLE -->

            <div class="admin-table-container">

                <table class="admin-table"
                       id="noticeTable">

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Message
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($notice = $result->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $notice["id"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $notice["title"]
                                        );
                                        ?>

                                    </strong>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $notice["message"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo date(
                                        "M d, Y",
                                        strtotime(
                                            $notice["created_at"]
                                        )
                                    );
                                    ?>

                                </td>


                                <td>

                                    <a
                                        href="notices.php?delete=<?php echo $notice["id"]; ?>"
                                        onclick="return confirm('Are you sure you want to delete this notice?');"
                                    >

                                        Delete

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="5"
                                class="no-data">

                                <div>
                                    📢
                                </div>

                                <strong>
                                    No notices found
                                </strong>

                                <p>
                                    Published notices will appear here.
                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>


    </main>

</div>



<!-- =================================================
     SEARCH SCRIPT
================================================= -->

<script>

const searchInput =
    document.getElementById("noticeSearch");

const table =
    document.getElementById("noticeTable");


searchInput.addEventListener(
    "keyup",
    function () {

        const value =
            searchInput.value.toLowerCase();

        const rows =
            table.querySelectorAll(
                "tbody tr"
            );


        rows.forEach(function (row) {

            const text =
                row.textContent.toLowerCase();

            if (text.includes(value)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    }
);

</script>


</body>

</html>