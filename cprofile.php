<?php
session_start();
include("config.php"); // DB connection

ob_start();
include("headerB.php"); // Use customer header if needed
ob_end_flush();

// Session validation
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "buyer") {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch basic customer data
$sql_user = "SELECT name, id, email, phone FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);

if (!$stmt_user) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$customer = $result_user->fetch_assoc();
$stmt_user->close();

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
    <title>Customer Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .profile-container {
            padding: 20px;
            margin: 20px;
        }
        .profile-container h2 {
            color: #007BFF;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Customer Profile</h2>

    <p><strong>Name:</strong> <?= displayValue($customer['name']); ?></p>
    <p><strong>ID:</strong> <?= displayValue($customer['id']); ?></p>
    <p><strong>Email:</strong> <?= displayValue($customer['email']); ?></p>
    <p><strong>Phone:</strong> <?= displayValue($customer['phone'] ?? ''); ?></p>

    <br>
    <a href="edit_customer_profile.php"><button>Edit Profile</button></a>
</div>

</body>
</html>
