<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact | UniBus</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
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
            <a href="contact.php" class="active">Contact</a>
        </div>

        <div class="nav-buttons">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>

    </nav>


    <!-- HEADER -->

    <section class="contact-header">

        <span class="section-label">
            CONTACT US
        </span>

        <h1>
            How Can We Help?
        </h1>

        <p>
            Have a question about bus schedules, bookings,
            or university transportation? Contact us.
        </p>

    </section>


    <!-- CONTACT SECTION -->

    <section class="contact-section">

        <div class="contact-container">


            <!-- CONTACT INFORMATION -->

            <div class="contact-info">

                <span class="section-label">
                    GET IN TOUCH
                </span>

                <h2>
                    We're Here to Help
                </h2>

                <p>
                    If you have any questions or problems related
                    to UniBus, feel free to contact the university
                    transportation team.
                </p>


                <div class="contact-item">

                    <div class="contact-icon">
                        📍
                    </div>

                    <div>

                        <h3>
                            Address
                        </h3>

                        <p>
                            Sylhet Engineering College
                        </p>

                    </div>

                </div>


                <div class="contact-item">

                    <div class="contact-icon">
                        📞
                    </div>

                    <div>

                        <h3>
                            Phone
                        </h3>

                        <p>
                            +880 1712-345678
                        </p>

                    </div>

                </div>


                <div class="contact-item">

                    <div class="contact-icon">
                        ✉️
                    </div>

                    <div>

                        <h3>
                            Email
                        </h3>

                        <p>
                            info@unibus.sec.edu.bd
                        </p>

                    </div>

                </div>


                <div class="contact-item">

                    <div class="contact-icon">
                        🕐
                    </div>

                    <div>

                        <h3>
                            Office Hours
                        </h3>

                        <p>
                            Sunday - Thursday<br>
                            9:00 AM - 4:00 PM
                        </p>

                    </div>

                </div>

            </div>


            <!-- CONTACT FORM -->

            <div class="contact-form-card">

                <h2>
                    Send Us a Message
                </h2>

                <p>
                    Fill out the form and we'll get back to you.
                </p>


                <form>

                    <div class="form-row">

                        <div class="form-group">

                            <label for="name">
                                Your Name
                            </label>

                            <input
                                type="text"
                                id="name"
                                placeholder="Enter your name"
                                required
                            >

                        </div>


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

                    </div>


                    <div class="form-group">

                        <label for="subject">
                            Subject
                        </label>

                        <input
                            type="text"
                            id="subject"
                            placeholder="What is your message about?"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="message">
                            Message
                        </label>

                        <textarea
                            id="message"
                            rows="6"
                            placeholder="Write your message here..."
                            required
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="contact-submit"
                    >
                        Send Message
                    </button>

                </form>

            </div>

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