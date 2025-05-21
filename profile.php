<?php
session_start();
include("config.php"); // DB connection

ob_start();
include("headerF.php");
ob_end_flush();

// Session validation
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "farmer") {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch basic user data
$sql_user = "SELECT name, id, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);

if (!$stmt_user) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$farmer = $result_user->fetch_assoc();
$stmt_user->close();

// Fetch extended farmer details
$sql_details = "SELECT * FROM farmer_details WHERE user_id = ?";
$stmt_details = $conn->prepare($sql_details);

if (!$stmt_details) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_details->bind_param("i", $user_id);
$stmt_details->execute();
$result_details = $stmt_details->get_result();
$farmer_details = $result_details->fetch_assoc();
$stmt_details->close();

$conn->close();

// Helper function
function displayValue($val) {
    return !empty($val) ? htmlspecialchars($val) : "<span style='color:gray;'>Not Provided</span>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .translate-container {
            position: absolute;
            top: 20px;
            left: 30px;
        }
        .translate-container select {
            padding: 5px;
            font-size: 14px;
        }
        .profile-container {
            padding: 20px;
            margin: 20px;
        }
        .profile-container h2 {
            color: green;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Farmer Profile</h2>

    <p><strong>Name:</strong> <?= displayValue($farmer['name']); ?></p>
    <p><strong>ID:</strong> <?= displayValue($farmer['id']); ?></p>
    <p><strong>Email:</strong> <?= displayValue($farmer['email']); ?></p>
    <p><strong>Phone:</strong> <?= displayValue($farmer['phone'] ?? ''); ?></p>


    <p><strong>Farm Name:</strong> <?= displayValue($farmer_details['farm_name'] ?? ''); ?></p>
    <p><strong>Location:</strong> <?= displayValue($farmer_details['location'] ?? ''); ?></p>
    <p><strong>Farming Type:</strong> <?= displayValue($farmer_details['farming_type'] ?? ''); ?></p>
    <p><strong>Years of Experience:</strong> <?= displayValue($farmer_details['experience'] ?? ''); ?></p>
    <p><strong>Farm Size (in acres):</strong> <?= displayValue($farmer_details['farm_size'] ?? ''); ?></p>
    <p><strong>Farming Practices:</strong> <?= displayValue($farmer_details['practices'] ?? ''); ?></p>
    <p><strong>Produce Grown:</strong> <?= displayValue($farmer_details['produce'] ?? ''); ?></p>
   
    <p><strong>Order Capacity:</strong> <?= displayValue($farmer_details['order_capacity'] ?? ''); ?></p>
    <p><strong>Packaging Info:</strong> <?= displayValue($farmer_details['packaging_info'] ?? ''); ?></p>
    <p><strong>Delivery Time:</strong> <?= displayValue($farmer_details['delivery_time'] ?? ''); ?></p>
    <p><strong>Bio:</strong> <?= displayValue($farmer_details['bio'] ?? ''); ?></p>

    <br>
    <a href="edit_profile.php"><button>Edit Profile</button></a>
</div>

<!-- Google Translate Script -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'hi,te,ta,kn,ml,gu,pa,mr,bn,ur,as,or,sd,ne,si,en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
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

    window.onload = function () {
        setTimeout(applySavedLanguage, 1000);
    };
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>
