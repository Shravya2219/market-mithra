<?php
session_start();
include("config.php");
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $user_type = $_POST["user_type"];

    // Save email in session now that it's defined
    $_SESSION['email'] = $email;

    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        $error = "Only alphabets and spaces are allowed in the name.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, one letter, one number, and one special character.";
    } else {
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            $_SESSION['temp_user'] = [
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "user_type" => $user_type,
                "otp" => $otp
            ];
            
// ✅ Add this line right below:
$_SESSION['otp_generated_time'] = time();

// Now send the OTP to user's email
mail($email, "Your OTP", "Your OTP is: $otp");
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'anushagoud0208@gmail.com'; // your email
                $mail->Password = 'vquo atxf kfhx amqs';        // your App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('anushagoud0208@gmail.com', 'MarketMitra');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "OTP Verification";
                $mail->Body = "Hello $name,<br><br>Your OTP for verification is: <b>$otp</b><br><br>Regards,<br>MarketMitra Team";

                $mail->send();
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $error = "OTP email could not be sent. Mailer Error: " . $mail->ErrorInfo;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MarketMitra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: space-between;
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
        }

        .signup-box {
            width: 80%;
            max-width: 400px;
            text-align: left;
        }

        .signup-box h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        .password-hint {
            font-size: 12px;
            color: gray;
            margin-top: 5px;
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

        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: green;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
<!-- Google Translate Dropdown -->
<div class="translate-container">
        <div id="google_translate_element"></div>
    </div>
    <div class="left-section"></div>

    <div class="right-section">
        <div class="logo">
            <a href="home.php"><img src="images/icons/logo.png" alt="MarketMitra Logo"></a>
        </div>

        <div class="signup-box">
            <h2>Register to MarketMitra</h2>
            <p>Create an account</p>

            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <span class="password-hint">Password must be at least 8 characters long, include one letter, one number, and one special character.</span>
                </div>

                <div class="input-group">
                    <label>User Type</label>
                    <select name="user_type" required>
                        <option value="farmer">Farmer</option>
                        <option value="buyer">Buyer</option>
                    </select>
                </div>

                

                <button type="submit" class="btn">Sign Up</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Login</a>
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
