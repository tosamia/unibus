<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | UniBus</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <!-- LOGO -->

        <div class="auth-logo">
            🚌 UniBus
        </div>


        <h1>Welcome Back</h1>

        <p class="auth-subtitle">
            Sign in to your UniBus account
        </p>


        <form action="#" method="POST">

            <!-- ROLE -->

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
                        🧑 Driver
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


            <!-- PASSWORD -->

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


            <!-- LOGIN -->

            <button
                type="submit"
                class="auth-button"
            >
                Login
            </button>

        </form>


        <!-- REGISTER AREA -->

        <div
            class="register-area"
            id="registerArea"
        >

            <p>
                Don't have an account?
            </p>

            <a
                href="registerr.php"
                id="registerLink"
            >
                Register as Student
            </a>

        </div>


        <!-- BACK -->

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
        document.getElementById(
            "registerArea"
        );

    const registerLink =
        document.getElementById(
            "registerLink"
        );


    // STUDENT

    if (selectedRole === "student") {

        registerArea.style.display = "block";

        registerLink.href =
            "register.php";

        registerLink.textContent =
            "Register as Student";

    }


    // DRIVER

    else if (selectedRole === "driver") {

        registerArea.style.display = "block";

        registerLink.href =
            "driver/register.php";

        registerLink.textContent =
            "Register as Driver";

    }


    // ADMIN

    else if (selectedRole === "admin") {

        registerArea.style.display = "none";

    }

}

</script>

</body>

</html>