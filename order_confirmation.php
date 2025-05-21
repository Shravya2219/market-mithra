<?php
session_start();
include 'config.php';
include 'headerB.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$customer_name = $_SESSION['username'];

// Save address
if (isset($_POST['submit_new_address'])) {
    $_SESSION['selected_address'] = [
        'home_no' => $_POST['home_no'],
        'street' => $_POST['street'],
        'mandal' => $_POST['mandal'],
        'district' => $_POST['district'],
        'state' => $_POST['state'],
        'pincode' => $_POST['pincode']
    ];
}

// Confirm address
if (isset($_POST['confirm_address'])) {
    $_SESSION['address_confirmed'] = true;
}

// Confirm order
if (isset($_POST['confirm_order']) && isset($_SESSION['address_confirmed'], $_SESSION['cart'])) {
    $address = json_encode($_SESSION['selected_address']);
    $cart_details = json_encode($_SESSION['cart']);
    
    // Calculate total
    $total_bill = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_bill += $item['quantity'] * $item['price'];
    }

    $order_number = uniqid('ORD_');
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $status = 'Pending';

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, customer_name, address, cart_details, total_amount, order_id, payment_method, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdsss", $customer_id, $customer_name, $address, $cart_details, $total_bill, $order_number, $payment_method, $status);

    if ($stmt->execute()) {
        // Update stock quantities
        foreach ($_SESSION['cart'] as $product_id => $item) {
            if (is_array($item)) {
                $update_stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
                $update_stmt->bind_param("iii", $item['quantity'], $product_id, $item['quantity']);
                $update_stmt->execute();
            }
        }

        // Clear cart
        unset($_SESSION['cart'], $_SESSION['selected_address'], $_SESSION['address_confirmed']);

        echo "<script>alert('Order placed successfully!'); window.location.href='orders.php';</script>";
        exit();
    } else {
        echo "<script>alert('Order placed successfully!'); window.location.href='homeB.php';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | MarketMitra</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { width: 80%; margin: auto; padding: 20px; }
        .btn { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Summary</h2>

    <h3>Your Cart</h3>
<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table border="1" width="100%" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $total_cost = 0;
        foreach ($_SESSION['cart'] as $item):
            if (is_array($item)):
                $total_cost += $item['quantity'] * $item['price'];
        ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td>₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
            </tr>
        <?php endif; endforeach; ?>
            <tr>
                <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>₹<?php echo number_format($total_cost, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>


    <!-- Address Form -->
    <?php if (!isset($_SESSION['selected_address'])): ?>
        <h3>Enter Shipping Address</h3>
        <form method="POST">
            <label>Home No:</label><br>
            <input type="text" name="home_no" required><br>
            
            <label>Street:</label><br>
            <input type="text" name="street" required><br>

            <label>Mandal:</label><br>
            <input type="text" name="mandal" required><br>

            <label>District:</label><br>
            <input type="text" name="district" required><br>

            <label>State:</label><br>
            <input type="text" name="state" required><br>

            <label>Pincode:</label><br>
            <input type="text" name="pincode" required><br>

            <button type="submit" name="submit_new_address" class="btn">Save Address</button>
        </form>

    <?php else: ?>
        <h3>Confirm Shipping Address</h3>
        <p><?php echo implode(', ', $_SESSION['selected_address']); ?></p>

        <?php if (!isset($_SESSION['address_confirmed'])): ?>
            <form method="POST">
                <button type="submit" name="confirm_address" class="btn">Confirm Address</button>
            </form>
        <?php endif; ?>

        <?php if (isset($_SESSION['address_confirmed'])): ?>
            <h3>Select Payment Method</h3>
            <form method="POST">
                <label><input type="radio" name="payment_method" value="cod" checked required> Cash on Delivery (COD)</label><br><br>
                <button type="submit" name="confirm_order" class="btn">Place Order</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
