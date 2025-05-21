<?php
session_start();
include("config.php");
require_once 'config.php';

require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

ob_start();
include("headerF.php");
ob_end_flush();

// Session check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "farmer") {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$otp_msg = "";
$success = "";

// Fetch existing data
$sql = "SELECT u.name, u.id, u.email, fd.* FROM users u LEFT JOIN farmer_details fd ON u.id = fd.user_id WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Twilio config (you must add these in config.php or here directly)
$twilio_sid = "YOUR_TWILIO_SID";
$twilio_token = "YOUR_TWILIO_TOKEN";
$twilio_number = "YOUR_TWILIO_PHONE_NUMBER";

// Handle OTP send
if (isset($_POST['send_otp'])) {
    $phone = $_POST['phone'];
    $_SESSION['otp'] = rand(100000, 999999);
    $_SESSION['temp_phone'] = $phone;

    $client = new Client($twilio_sid, $twilio_token);
    try {
        $client->messages->create(
            "+91$phone",
            [
                'from' => $twilio_number,
                'body' => "Your OTP for MarketMitra is " . $_SESSION['otp']
            ]
        );
        $otp_msg = "OTP sent to your phone!";
    } catch (Exception $e) {
        $otp_msg = "Failed to send OTP: " . $e->getMessage();
    }
}

// Handle OTP verify
if (isset($_POST['verify_otp'])) {
    if ($_POST['entered_otp'] == $_SESSION['otp']) {
        $_SESSION['verified_phone'] = $_SESSION['temp_phone'];
        $otp_msg = "Phone number verified!";
    } else {
        $otp_msg = "Invalid OTP!";
    }
}

// Save profile only if phone is verified
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['save_profile'])) {
    if (!isset($_SESSION['verified_phone'])) {
        $success = "Please verify your phone number before saving.";
    } else {
        $farm_name = $_POST['farm_name'];
        $location = $_POST['location'];
        $farming_type = $_POST['farming_type'];
        $experience = $_POST['experience'];
        $farm_size = $_POST['farm_size'];
        $practices = $_POST['practices'];
        $produce = $_POST['produce'];
        $certifications = $_POST['certifications'];
        $order_capacity = $_POST['order_capacity'];
        $packaging_info = $_POST['packaging_info'];
        $delivery_time = $_POST['delivery_time'];
        $bio = $_POST['bio'];
        $phone = $_SESSION['verified_phone'];

        // Update phone in users table
        $stmt1 = $conn->prepare("UPDATE users SET phone=? WHERE id=?");
        $stmt1->bind_param("si", $phone, $user_id);
        $stmt1->execute();
        $stmt1->close();

        // Check if entry exists
        $check = $conn->prepare("SELECT user_id FROM farmer_details WHERE user_id=?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $res = $check->get_result();
        $exists = $res->num_rows > 0;
        $check->close();

        if ($exists) {
            // Update
            $sql_update = "UPDATE farmer_details SET farm_name=?, location=?, farming_type=?, experience=?, farm_size=?, practices=?, produce=?, certifications=?, order_capacity=?, packaging_info=?, delivery_time=?, bio=? WHERE user_id=?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssssssssssssi", $farm_name, $location, $farming_type, $experience, $farm_size, $practices, $produce, $certifications, $order_capacity, $packaging_info, $delivery_time, $bio, $user_id);
        } else {
            // Insert
            $sql_insert = "INSERT INTO farmer_details (user_id, farm_name, location, farming_type, experience, farm_size, practices, produce, certifications, order_capacity, packaging_info, delivery_time, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("issssssssssss", $user_id, $farm_name, $location, $farming_type, $experience, $farm_size, $practices, $produce, $certifications, $order_capacity, $packaging_info, $delivery_time, $bio);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
        } else {
            $success = "Something went wrong!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .form-container {
            padding: 30px;
            margin: 40px;
            max-width: 600px;
            background: #f8f8f8;
            border-radius: 12px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin: 6px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
            border-radius: 10px;
        }
        .msg { color: blue; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Profile</h2>
    <?php if ($success) echo "<p class='msg'>$success</p>"; ?>
    <form method="post">
        <label>Phone Number:</label>
        <input type="text" name="phone" value="<?= $_SESSION['verified_phone'] ?? ($data['phone'] ?? '') ?>" required>
        <input type="submit" name="send_otp" value="Verify with OTP"><br>

        <?php if (isset($_POST['send_otp'])): ?>
            <label>Enter OTP:</label>
            <input type="text" name="entered_otp" required>
            <input type="submit" name="verify_otp" value="Verify OTP">
        <?php endif; ?>

        <?php if ($otp_msg) echo "<p class='msg'>$otp_msg</p>"; ?>

        <label>Farm Name:</label>
        <input type="text" name="farm_name" value="<?= $data['farm_name'] ?? '' ?>">

        <label>Location:</label>
        <input type="text" name="location" value="<?= $data['location'] ?? '' ?>">

        <label>Farming Type:</label>
        <input type="text" name="farming_type" value="<?= $data['farming_type'] ?? '' ?>">

        <label>Experience (in years):</label>
        <input type="text" name="experience" value="<?= $data['experience'] ?? '' ?>">

        <label>Farm Size (acres):</label>
        <input type="text" name="farm_size" value="<?= $data['farm_size'] ?? '' ?>">

        <label>Farming Practices:</label>
        <textarea name="practices"><?= $data['practices'] ?? '' ?></textarea>

        <label>Produce Grown:</label>
        <textarea name="produce"><?= $data['produce'] ?? '' ?></textarea>

        <label>Certifications:</label>
        <textarea name="certifications"><?= $data['certifications'] ?? '' ?></textarea>

        <label>Order Capacity:</label>
        <input type="text" name="order_capacity" value="<?= $data['order_capacity'] ?? '' ?>">

        <label>Packaging Info:</label>
        <textarea name="packaging_info"><?= $data['packaging_info'] ?? '' ?></textarea>

        <label>Delivery Time:</label>
        <input type="text" name="delivery_time" value="<?= $data['delivery_time'] ?? '' ?>">

        <label>Bio:</label>
        <textarea name="bio"><?= $data['bio'] ?? '' ?></textarea>

        <br>
        <input type="submit" name="save_profile" value="Save Changes">
    </form>
</div>

</body>
</html>
