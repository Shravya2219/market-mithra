<?php
session_start();
include("config.php");

$success_message = '';
if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MarketMitra</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        .left-section {
            width: 55%;
            background: url('images/login_bg.jpg') no-repeat center center/cover;
        }

        .right-section {
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .logo img {
            height: 40px;
            cursor: pointer;
        }

        .login-box {
            width: 80%;
            max-width: 400px;
            text-align: left;
        }

        .login-box h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .login-box p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }

        .signup {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup a {
            color: green;
            text-decoration: none;
            font-weight: bold;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        /* Google Translate Dropdown */
        .translate-container {
            position: absolute;
            top: 20px;
            left: 30px;
        }

        .translate-container select {
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Google Translate Dropdown -->
    <div class="translate-container">
        <div id="google_translate_element"></div>
    </div>

    <!-- Left Image Section -->
    <div class="left-section"></div>

    <!-- Right Login Form Section -->
    <div class="right-section">
        <!-- Clickable Logo -->
        <div class="logo">
            <a href="home.php"><img src="images/icons/logo.png" alt="MarketMitra Logo"></a>
        </div>

        <div class="login-box">
    <h2>Welcome to MarketMitra</h2>
    <p>Please enter your details</p>

    <?php if (!empty($success_message)) : ?>
        <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
    <?php endif; ?>

  

            <form action="process_login.php" method="POST">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="farmer">Farmer</option>
                        <option value="buyer">Buyer</option>
                    </select>
                </div>

                <button type="submit" class="btn">Login</button>
            </form>

            <div class="signup">
                Don't have an account? <a href="signup.php">Sign up</a>
            </div>
        </div>
    </div>

    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'hi,te,ta,kn,ml,gu,pa,mr,bn,ur,as,or,sd,ne,si,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }

        function applySavedLanguage() {
            var savedLanguage = localStorage.getItem("selectedLanguage");
            if (savedLanguage) {
                var googleFrame = document.querySelector(".goog-te-combo");
                if (googleFrame) {
                    googleFrame.value = savedLanguage;
                    googleFrame.dispatchEvent(new Event("change"));
                }
            }
        }

        // Wait for Google Translate to load and apply language
        window.onload = function() {
            setTimeout(applySavedLanguage, 1000);
        };
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>
