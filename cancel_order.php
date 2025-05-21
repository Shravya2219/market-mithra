<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    die("Access Denied");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $customer_id = $_SESSION['user_id'];

    // Fetch order details
    $stmt = $conn->prepare("SELECT order_date, order_status FROM orders WHERE order_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($order_date, $order_status);
    $stmt->fetch();

    if ($stmt->num_rows === 0) {
        echo "Order not found.";
        exit;
    }

    $order_time = strtotime($order_date);
    $current_time = time();
    $time_diff = $current_time - $order_time;

    if ($order_status !== 'Pending') {
        echo "Order is already processed and cannot be canceled.";
    } elseif ($time_diff > 60) {
        echo "Cancellation time has expired.";
    } else {
        // Update order status
        $update_stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?");
        $update_stmt->bind_param("i", $order_id);
        if ($update_stmt->execute()) {
            echo "Order successfully canceled.";
        } else {
            echo "Failed to cancel order.";
        }
        $update_stmt->close();
    }

    $stmt->close();
}
?>
