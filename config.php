<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketmithra"; // Ensure the database name is correct

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
<?php
$twilio_sid = "ACea03ae4c93c14d35ea24bae690c01449";
$twilio_token = "4b6e85f3d4b1abff9b168685632ff871";
$twilio_number = "+17742069886";
?>
