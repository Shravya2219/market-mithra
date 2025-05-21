<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_id = $_POST['review_id'];

    // Delete review from database
    $query = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Review deleted successfully.";
    } else {
        $_SESSION['msg'] = "Error deleting review.";
    }

    $stmt->close();
    $conn->close();
}

header("Location: my_orders.php");
exit();
?>
