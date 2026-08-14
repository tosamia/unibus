<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "";

    if ($email === "" || $password === "" || $role === "") {

        $error = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, student_id, department, email, password, role
             FROM users
             WHERE email = ? AND role = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $error = "Database error. Please try again.";

        } else {

            $stmt->bind_param("ss", $email, $role);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $user = $result->fetch_assoc();

                if (password_verify($password, $user["password"])) {

                    // Clear old session data
                    session_regenerate_id(true);

                    // Save user information
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["student_id"] = $user["student_id"];
                    $_SESSION["department"] = $user["department"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];

                    // Redirect according to role

                    if ($user["role"] === "student") {

                        header("Location: student-dashboard.php");
                        exit;

                    }

                    if ($user["role"] === "driver") {

                        header("Location: driver/driver-dashboard.php");
                        exit;

                    }

                    if ($user["role"] === "admin") {

                        header("Location: admin/admin-dashboard.php");
                        exit;

                    }

                    $error = "Invalid account role.";

                } else {

                    $error = "Incorrect password.";

                }

            } else {

                $error = "No account found with this email and selected role.";

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | UniBus</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-logo">
            🚌 UniBus
        </div>

        <h1>Welcome Back</h1>

        <p class="auth-subtitle">
            Sign in to your UniBus account
        </p>


        <?php if ($error !== ""): ?>

            <div class="login-error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <?php if (isset($_GET["registered"])): ?>

            <div class="login-success">

                Registration successful!
                Please login.

            </div>

        <?php endif; ?>


        <form
            action="login.php"
            method="POST"
        >

            <label>
                Login As
            </label>


            <div class="role-options">

                <!-- STUDENT -->

                <label class="role-option">

                    <input
                        type="radio"
                        name="role"
                        value="student"
                        checked
                        onchange="updateRegister()"
                    >

                    <span>
                        👨‍🎓 Student
                    </span>

                </label>


                <!-- DRIVER -->

                <label class="role-option">

                    <input
                        type="radio"
                        name="role"
                        value="driver"
                        onchange="updateRegister()"
                    >

                    <span>
                        🧑‍✈️ Driver
                    </span>

                </label>


                <!-- ADMIN -->

                <label class="role-option">

                    <input
                        type="radio"
                        name="role"
                        value="admin"
                        onchange="updateRegister()"
                    >

                    <span>
                        👨‍💼 Admin
                    </span>

                </label>

            </div>


            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >


            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >


            <button
                type="submit"
                class="auth-button"
            >
                Login
            </button>

        </form>


        <div
            class="register-area"
            id="registerArea"
        >

            <p>
                Don't have an account?
            </p>

            <a
                href="register.php"
                id="registerLink"
            >
                Register as Student
            </a>

        </div>


        <a
            href="index.php"
            class="back-link"
        >
            ← Back to Home
        </a>

    </div>

</div>


<script>

function updateRegister() {

    const selectedRole =
        document.querySelector(
            'input[name="role"]:checked'
        ).value;

    const registerArea =
        document.getElementById("registerArea");

    const registerLink =
        document.getElementById("registerLink");


    if (selectedRole === "student") {

        registerArea.style.display = "block";

        registerLink.href = "register.php";

        registerLink.textContent =
            "Register as Student";

    }


    else if (selectedRole === "driver") {

        registerArea.style.display = "block";

        registerLink.href =
            "driver/register.php";

        registerLink.textContent =
            "Register as Driver";

    }


    else if (selectedRole === "admin") {

        registerArea.style.display = "none";

    }

}

</script>

</body>

</html>