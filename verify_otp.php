<?php
session_start();

if (!isset($_SESSION['temp_user'])) {
    header("Location: signup.php");
    exit();
}

// Check if OTP has expired (5 minutes = 300 seconds)
if (isset($_SESSION['otp_generated_time']) && (time() - $_SESSION['otp_generated_time'] > 300)) {
    unset($_SESSION['temp_user']);
    $error = "OTP expired. Please register again.";
    header("Location: signup.php?error=" . urlencode($error));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST["otp"]);

    if ($entered_otp == $_SESSION['temp_user']['otp']) {
        include("config.php");

        $name = $_SESSION['temp_user']['name'];
        $email = $_SESSION['temp_user']['email'];
        $password = $_SESSION['temp_user']['password'];
        $user_type = $_SESSION['temp_user']['user_type'];

        $insert = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssss", $name, $email, $password, $user_type);
        if ($insert->execute()) {
            unset($_SESSION['temp_user']);
            unset($_SESSION['otp_generated_time']);
            $_SESSION['success'] = "OTP Verified Successfully! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Something went wrong while saving user.";
        }
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification - MarketMitra</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
        }

        .left-section {
            width: 55%;
            background: url('images/login_bg.jpg') no-repeat center center/cover;
        }

        .right-section {
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            flex-direction: column;
            padding: 40px;
            text-align: center;
        }

        .otp-box {
            width: 100%;
            max-width: 400px;
            text-align: left;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .timer {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
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

    <div class="left-section"></div>

    <div class="right-section">
        <div class="logo">
            <a href="home.php"><img src="images/icons/logo.png" alt="Logo"></a>
        </div>

        <div class="otp-box">
            <h2>OTP Verification</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Enter OTP sent to your email:</label>
                    <input type="text" name="otp" required>
                </div>
                <button type="submit" class="btn">Verify</button>
            </form>

            <div class="timer">
                OTP will expire in <span id="countdown">05:00</span>
            </div>
        </div>
    </div>

    <script>
        let seconds = 300;
        const countdownEl = document.getElementById("countdown");

        function updateCountdown() {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            countdownEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            if (seconds > 0) {
                seconds--;
                setTimeout(updateCountdown, 1000);
            } else {
                countdownEl.textContent = "Expired!";
                alert("OTP has expired. Please register again.");
                window.location.href = "signup.php";
            }
        }

        updateCountdown();
    </script>

</body>
</html>
