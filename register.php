```php
<?php
session_start();

require_once "config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $student_id = trim($_POST["student_id"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $department = trim($_POST["department"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Check passwords
    if ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        // Check whether email or student ID already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ? OR student_id = ?"
        );

        $check->bind_param("ss", $email, $student_id);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email or Student ID already exists.";
            $message_type = "error";

        } else {

            // Securely hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // New students are registered as student
            $role = "student";

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, student_id, department, email, password, role)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $name,
                $student_id,
                $department,
                $email,
                $hashed_password,
                $role
            );

            if ($stmt->execute()) {

                header("Location: login.php?registered=1");
                exit;

            } else {

                $message = "Registration failed. Please try again.";
                $message_type = "error";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | UniBus</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo">
            🚌 UniBus
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="schedule.php">Bus Schedule</a>
            <a href="notices.php">Notices</a>
            <a href="contact.php">Contact</a>
        </div>

        <div class="nav-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>

    </nav>


    <!-- REGISTER SECTION -->

    <section class="auth-section">

        <div class="auth-card register-card">

            <div class="auth-icon">
                🚌
            </div>

            <h1>Create Account</h1>

            <p class="auth-subtitle">
                Register for your UniBus account
            </p>

            <?php if ($message): ?>

                <div class="auth-message <?= htmlspecialchars($message_type) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endif; ?>


            <form action="register.php" method="POST">

                <!-- FULL NAME -->

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <!-- STUDENT ID -->

                <div class="form-group">

                    <label for="student_id">
                        Student ID
                    </label>

                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        placeholder="Enter your student ID"
                        required
                    >

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <!-- DEPARTMENT -->

                <div class="form-group">

                    <label for="department">
                        Department
                    </label>

                    <select id="department" name="department" required>

                        <option value="">
                            Select your department
                        </option>

                        <option value="cse">
                            Computer Science & Engineering
                        </option>

                        <option value="eee">
                            Electrical & Electronic Engineering
                        </option>

                        <option value="civil">
                            Civil Engineering
                        </option>

                        <option value="mechanical">
                            Mechanical Engineering
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        required
                    >

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Confirm your password"
                        required
                    >

                </div>


                <!-- TERMS -->

                <div class="register-terms">

                    <label>

                        <input type="checkbox" required>

                        I agree to the UniBus terms and conditions.

                    </label>

                </div>


                <!-- REGISTER BUTTON -->

                <button type="submit" class="auth-submit">
                    Create Account
                </button>

            </form>


            <p class="auth-footer">

                Already have an account?

                <a href="login.php">
                    Login Now
                </a>

            </p>

        </div>

    </section>


    <!-- FOOTER -->

    <footer class="footer">

        <div class="footer-content">

            <div>

                <h3>🚌 UniBus</h3>

                <p>
                    University Bus Booking and Management System
                </p>

            </div>


            <div>

                <h3>Quick Links</h3>

                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="schedule.php">Bus Schedule</a>
                <a href="notices.php">Notices</a>

            </div>


            <div>

                <h3>Contact Info</h3>

                <p>📞 +880 1712-345678</p>
                <p>✉ info@unibus.sec.edu.bd</p>
                <p>📍 Sylhet Engineering College</p>

            </div>

        </div>


        <div class="footer-bottom">

            © 2026 UniBus. All rights reserved.

        </div>

    </footer>

</body>

</html>
```
