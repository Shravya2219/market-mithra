<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "buyer") {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_otp'])) {
    $_SESSION['temp_name'] = $_POST['name'];
    $_SESSION['temp_email'] = $_POST['email'];
    $_SESSION['temp_phone'] = $_POST['phone'];

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;

    // For testing purposes, display it (Replace with actual SMS/email sending logic)
    $success = "OTP sent successfully. <strong>OTP: $otp</strong> (showing here for demo)";

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        // Save updated data
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $_SESSION['temp_name'], $_SESSION['temp_email'], $_SESSION['temp_phone'], $user_id);
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
        } else {
            $errors[] = "Failed to update profile.";
        }
        $stmt->close();
        unset($_SESSION['otp']);
    } else {
        $errors[] = "Invalid OTP entered.";
    }
}

// Fetch current data
$sql = "SELECT name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - Customer</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container { width: 50%; margin: auto; padding: 20px; }
        input, button { padding: 8px; margin: 8px 0; width: 100%; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Customer Profile</h2>

    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php foreach ($errors as $err) echo "<p class='error'>$err</p>"; ?>

    <?php if (!isset($_SESSION['otp'])): ?>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($current['name']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($current['email']) ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($current['phone']) ?>" required>

            <button type="submit" name="send_otp">Send OTP</button>
        </form>
    <?php else: ?>
        <form method="POST">
            <label>Enter OTP sent to your phone/email:</label>
            <input type="text" name="otp" required>

            <button type="submit" name="verify_otp">Verify & Update</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
