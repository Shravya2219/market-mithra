<?php
session_start();
include 'config.php';
include 'headerB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'buyer') {
    die("<p style='text-align:center; color:red;'>Access Denied. Please log in as a customer.</p>");
}
$customer_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MarketMitra - My Orders</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0; padding: 0;
        background-color: #f4f4f4;
    }
    .order-container {
        max-width: 800px; margin: 20px auto; padding: 20px;
        background: white; border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; color: #333; }
    .order {
        border: 1px solid #ddd; padding: 15px; margin: 15px 0;
        background-color: #fafafa; border-radius: 5px;
    }
    .order h3 { margin: 0; padding-bottom: 10px; color: #0275d8; }
    .order p { margin: 5px 0; font-size: 14px; }
    .status {
        font-weight: bold; padding: 5px; border-radius: 5px;
    }
    .pending { background-color: orange; color: white; }
    .shipped { background-color: blue; color: white; }
    .delivered { background-color: green; color: white; }
    .cancelled { background-color: red; color: white; }
    .cancel-btn {
        background-color: red; color: white; border: none;
        padding: 5px 10px; cursor: pointer; border-radius: 5px;
        margin-top: 10px;
    }
    .cancel-btn:disabled {
        background-color: gray; cursor: not-allowed;
    }
    .review-form {
        margin-top: 15px;
    }
    .review-form textarea, .review-form select {
        width: 100%; padding: 8px; margin-top: 5px;
    }
    .review-form button {
        background-color: #28a745; color: white;
        padding: 6px 12px; border: none;
        border-radius: 5px; margin-top: 10px; cursor: pointer;
    }
    .review-display {
        margin-top: 10px;
        background-color: #eee;
        padding: 10px; border-radius: 5px;
    }
    .review-actions {
        margin-top: 5px;
    }
    .review-actions button {
        margin-right: 10px; padding: 4px 8px; cursor: pointer;
    }
  </style>
</head>
<body>

<div class="order-container">
  <h2>My Orders</h2>

<?php
if ($stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC")) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_id = htmlspecialchars($row['order_id']);
            echo "<div class='order'>";
            echo "<p><strong>Order ID:</strong> $order_id</p>";
            echo "<p><strong>Total Amount:</strong> ₹" . number_format($row['total_amount'], 2) . "</p>";
            echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($row['payment_method']) . "</p>";
            echo "<p><strong>Order Date:</strong> " . htmlspecialchars($row['order_date']) . "</p>";
            echo "<p><strong>Order Status:</strong> " . htmlspecialchars($row['order_status']) . "</p>";

            // Cancel Button only for 'Pending' orders
            $isPending = $row['order_status'] === 'Pending';
            echo "<button class='cancel-btn' onclick='cancelOrder(\"$order_id\")' " . (!$isPending ? 'disabled' : '') . ">Cancel Order</button>";

            // Show Review section if Delivered
            if ($row['order_status'] === 'Delivered') {
                $review_stmt = $conn->prepare("SELECT * FROM ratings WHERE order_id = ? AND customer_id = ?");
                $review_stmt->bind_param("si", $order_id, $customer_id);
                $review_stmt->execute();
                $review_result = $review_stmt->get_result();

                if ($review_result && $review_result->num_rows > 0) {
                    $review = $review_result->fetch_assoc();
                    echo "<div class='review-display' id='review-display-$order_id'>";
                    echo "<p><strong>Your Rating:</strong> " . str_repeat("⭐", (int)$review['rating']) . "</p>";
                    echo "<p><strong>Your Review:</strong> " . htmlspecialchars($review['review']) . "</p>";
                    echo '<div class="review-actions">';
                    echo '<button onclick="editReview(`' . $order_id . '`, ' . $review['rating'] . ', `' . htmlspecialchars($review['review'], ENT_QUOTES) . '`)">Edit</button>';
                    echo '<button onclick="deleteReview(`' . $order_id . '`)">Delete</button>';
                    echo '</div></div>';
                } else {
                    echo '<form method="POST" action="submit_review.php" class="review-form">';
                    echo '<label for="rating">Rate this order:</label>';
                    echo '<select name="rating" required>
                            <option value="">Select rating</option>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                          </select>';
                    echo '<label for="review">Write a review:</label>';
                    echo '<textarea name="review" rows="3" required></textarea>';
                    echo "<input type='hidden' name='order_id' value='$order_id'>";
                    echo "<input type='hidden' name='customer_id' value='$customer_id'>";
                    echo '<button type="submit">Submit Review</button>';
                    echo '</form>';
                }
                $review_stmt->close();
            }

            echo "</div><hr>";
        }
    } else {
        echo "<p style='text-align:center; color:gray;'>No orders found.</p>";
    }
    $stmt->close();
}
?>
</div>

<?php if (isset($_GET['review'])): ?>
<script>
    <?php if ($_GET['review'] === 'success'): ?>
        alert("Review submitted successfully!");
    <?php elseif ($_GET['review'] === 'updated'): ?>
        alert("Review updated successfully!");
    <?php endif; ?>
</script>
<?php endif; ?>

<script>
function cancelOrder(orderId) {
    if (confirm("Are you sure you want to cancel this order?")) {
        $.ajax({
            url: "cancel_order.php",
            type: "POST",
            data: { order_id: orderId },
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function() {
                alert("Error canceling order.");
            }
        });
    }
}

function editReview(orderId, rating, text) {
    const form = `
        <form method="POST" action="update_review.php" class="review-form">
            <label for="rating">Update Rating:</label>
            <select name="rating" required>
                <option value="">Select rating</option>
                <option value="5" ${rating == 5 ? 'selected' : ''}>⭐⭐⭐⭐⭐</option>
                <option value="4" ${rating == 4 ? 'selected' : ''}>⭐⭐⭐⭐</option>
                <option value="3" ${rating == 3 ? 'selected' : ''}>⭐⭐⭐</option>
                <option value="2" ${rating == 2 ? 'selected' : ''}>⭐⭐</option>
                <option value="1" ${rating == 1 ? 'selected' : ''}>⭐</option>
            </select>
            <label for="review">Update Review:</label>
            <textarea name="review" rows="3" required>${text}</textarea>
            <input type="hidden" name="order_id" value="${orderId}">
            <button type="submit">Update Review</button>
        </form>
    `;
    document.getElementById('review-display-' + orderId).innerHTML = form;
}

function deleteReview(orderId) {
    if (confirm("Are you sure you want to delete this review?")) {
        $.ajax({
            url: "delete_review.php",
            type: "POST",
            data: { order_id: orderId },
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function() {
                alert("Error deleting review.");
            }
        });
    }
}
</script>
</body>
</html>
