<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Basic validation
    if (!empty($order_id) && !empty($customer_id) && !empty($rating) && !empty($review)) {
        // Check if review already exists
        $check_stmt = $conn->prepare("SELECT * FROM ratings WHERE order_id = ? AND customer_id = ?");
        $check_stmt->bind_param("si", $order_id, $customer_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update existing review
            $update_stmt = $conn->prepare("UPDATE ratings SET rating = ?, review = ? WHERE order_id = ? AND customer_id = ?");
            $update_stmt->bind_param("issi", $rating, $review, $order_id, $customer_id);
            $update_stmt->execute();
            $update_stmt->close();
            $conn->close();
            header("Location: my_orders.php?review=updated");
            exit();
            
        } else {
            // Insert new review
            $stmt = $conn->prepare("INSERT INTO ratings (order_id, customer_id, rating, review) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $order_id, $customer_id, $rating, $review);
            $stmt->execute();
            $stmt->close();
        }

        $check_stmt->close();
        $conn->close();

        // Redirect to show alert
        header("Location: my_orders.php?review=success");
        exit();
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request.";
}
?>
