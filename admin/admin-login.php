<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | UniBus</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<section class="login-section">

    <div class="login-card">

        <div class="logo">
            🚌 UniBus
        </div>

        <h1>Admin Login</h1>

        <p>Sign in to manage the UniBus system.</p>

        <form action="#" method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Admin email"
                required
            >

            <label>Password</label>

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