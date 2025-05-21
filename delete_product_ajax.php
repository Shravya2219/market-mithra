<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$farmer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $product_id = intval($_POST['id']);

    if ($stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND farmer_id = ?")) {
        $stmt->bind_param("ii", $product_id, $farmer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            if ($delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?")) {
                $delete_stmt->bind_param("i", $product_id);
                $delete_stmt->execute();

                if ($delete_stmt->affected_rows > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error deleting product.']);
                }

                $delete_stmt->close();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found or unauthorized.']);
        }

        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
