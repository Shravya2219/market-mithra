<?php
// update_review.php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $customer_id = $_SESSION['user_id']; // Or use $_POST['customer_id'] if not using session

    if ($stmt = $conn->prepare("UPDATE ratings SET rating = ?, review = ? WHERE order_id = ? AND customer_id = ?")) {
        $stmt->bind_param("issi", $rating, $review, $order_id, $customer_id);
        if ($stmt->execute()) {
            header("Location: my_orders.php?review=updated");
            exit();
        } else {
            echo "Failed to update review.";
        }
        $stmt->close();
    } else {
        echo "Statement preparation failed.";
    }
} else {
    echo "Invalid request.";
}
?>
