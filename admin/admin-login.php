<?php

session_start();

require_once "../config/database.php";

$error = "";

/* ==========================================
   IF ALREADY LOGGED IN AS ADMIN
========================================== */

if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {

    if ($_SESSION["role"] === "admin") {
        header("Location: admin-dashboard.php");
        exit;
    }
}


/* ==========================================
   ADMIN LOGIN
========================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $error = "Please enter email and password.";

    } else {

        $sql = "SELECT id, name, email, password, role
                FROM users
                WHERE email = ?
                AND role = 'admin'
                LIMIT 1";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $error = "Database error: " . $conn->error;

        } else {

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $admin = $result->fetch_assoc();


                /* ==========================================
                   CHECK PASSWORD
                ========================================== */

                if (password_verify($password, $admin["password"])) {

                    /*
                       Clear old session first
                    */

                    session_unset();
                    session_regenerate_id(true);


                    /*
                       Create admin session
                    */

                    $_SESSION["user_id"] = $admin["id"];
                    $_SESSION["name"] = $admin["name"];
                    $_SESSION["email"] = $admin["email"];
                    $_SESSION["role"] = "admin";


                    /*
                       Go to dashboard
                    */

                    header("Location: admin-dashboard.php");
                    exit;

                } else {

                    $error = "Invalid admin email or password.";

                }

            } else {

                $error = "Invalid admin email or password.";

            }

            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | UniBus</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<section class="login-section">

    <div class="login-card">

        <div class="logo">
            🚌 UniBus
        </div>

        <h1>
            Admin Login
        </h1>

        <p>
            Sign in to manage the UniBus system.
        </p>


        <?php if ($error !== ""): ?>

            <div class="error-message">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form method="POST"
              action="admin-login.php">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Admin email"
                required
            >


            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >


            <button type="submit">

                Login as Admin

            </button>

        </form>


        <a href="../login.php">

            ← Back to Login

        </a>

    </div>

</section>

</body>

</html>