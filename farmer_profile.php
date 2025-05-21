<?php
session_start();
include 'config.php'; // Database connection file
include 'headerB.php'; 
// Get the farmer's ID from the URL
$farmer_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $farmer = $result->fetch_object();
} else {
    die("Farmer not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile | MarketMitra</title>
    <style>
        /* Custom styles for the profile page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }
        .logo {
            height: 50px;
            cursor: pointer;
        }
        .icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .icons img {
            height: 30px;
            cursor: pointer;
        }
        .menu-icon {
            font-size: 30px;
            cursor: pointer;
        }
        .menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 10px;
            background: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        .menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .menu ul li {
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .menu ul li:last-child {
            border-bottom: none;
        }
        .menu ul li a {
            text-decoration: none;
            color: black;
            display: block;
        }
        .profile-container {
            padding: 20px;
        }
        .profile-container h2 {
            font-size: 2em;
        }
        .profile-container p {
            font-size: 1.2em;
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
        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
        }
    </style>
</head>
<body>


    <!-- Farmer Profile Section -->
    <div class="profile-container">
        <h2><?php echo htmlspecialchars($farmer->name ?? 'Name not provided', ENT_QUOTES, 'UTF-8'); ?></h2>
        <img src="images/icons/<?php echo htmlspecialchars($farmer->profile_img ?? 'user.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Farmer Image">
        <p><strong>Farmer ID:</strong> <?php echo htmlspecialchars($farmer->id ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></p>

        <p><strong>Email:</strong> <?php echo htmlspecialchars($farmer->email ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($farmer->address ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($farmer->city ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($farmer->phone ?? 'Not provided', ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

</body>
</html>

<?php
$conn->close();
?>
