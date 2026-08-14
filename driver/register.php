

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Driver Registration | UniBus</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <!-- LOGO -->

        <div class="auth-logo">
            🚌 UniBus
        </div>


        <h1>Driver Registration</h1>

        <p class="auth-subtitle">
            Create your UniBus driver account
        </p>


        <form action="#" method="POST">

            <!-- FULL NAME -->

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


            <!-- DRIVER ID -->

            <label for="driver_id">
                Driver ID
            </label>

            <input
                type="text"
                id="driver_id"
                name="driver_id"
                placeholder="Enter your driver ID"
                required
            >


            <!-- EMAIL -->

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


            <!-- PHONE -->

            <label for="phone">
                Phone Number
            </label>

            <input
                type="tel"
                id="phone"
                name="phone"
                placeholder="Enter your phone number"
                required
            >


            <!-- PASSWORD -->

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


            <!-- CONFIRM PASSWORD -->

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


            <!-- REGISTER -->

            <button
                type="submit"
                class="auth-button"
            >
                Create Driver Account
            </button>

        </form>


        <!-- LOGIN LINK -->

        <div class="register-area">

            <p>
                Already have an account?
            </p>

            <a href="../login.php">
                ← Back to Login
            </a>

        </div>

    </div>

</div>

</body>

</html>