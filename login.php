<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | UniBus</title>

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


    <!-- LOGIN SECTION -->

    <section class="auth-section">

        <div class="auth-card">

            <div class="auth-icon">
                🚌
            </div>

            <h1>Welcome Back!</h1>

            <p class="auth-subtitle">
                Login to your UniBus account
            </p>


            <form>

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <div class="login-options">

                    <label>
                        <input type="checkbox">
                        Remember me
                    </label>

                    <a href="#">
                        Forgot Password?
                    </a>

                </div>


                <button type="submit" class="auth-submit">
                    Login
                </button>

            </form>


            <p class="auth-footer">

                Don't have an account?

                <a href="register.php">
                    Register Now
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