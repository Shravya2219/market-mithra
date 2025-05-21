<?php
include 'config.php';
include 'headerF.php'; // Optional: If you're using a common header

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Orders - MarketMitra</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #0275d8;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
  </style>
</head>
<body>

<div class="container">
    <h2>All Orders</h2>

    <?php
    $query = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Total Amount (₹)</th>
                <th>Payment Method</th>
                <th>Order Date</th>
                <th>Order Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['customer_id']) ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['order_status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:gray;">No orders found.</p>
    <?php endif;

    $conn->close();
    ?>
</div>

</body>
</html>
