<?php
session_start();
include 'config.php'; // Database connection file
include 'headerB.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart when the user clicks "Add to Cart"
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    // Fetch product details
    $query = "SELECT p.product_name, p.price, p.product_img_name
              FROM products p 
              WHERE p.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_object();
        // Add product to cart (if not already added)
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product->product_name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->product_img_name
            ];
        } else {
            // If product already in the cart, just increment quantity
            $_SESSION['cart'][$product_id]['quantity']++;
        }
    }
}

// Empty the cart
if (isset($_GET['empty_cart'])) {
    unset($_SESSION['cart']);
}

// Calculate total cost
$total_cost = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item)) {
            $total_cost += $item['quantity'] * $item['price'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart | MarketMitra</title>
    <style>
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
        .translate-container {
            position: absolute;
            top: 20px;
            left: 30px;
        }
        .translate-container select {
            padding: 5px;
            font-size: 14px;
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
        .cart-container {
            padding: 20px;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-table th, .cart-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .cart-table th {
            background-color: #f4f4f4;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="cart-container">
        <h2>Your Shopping Cart</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
            <a href="products.php" class="btn">Continue Shopping</a>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                        <?php if (is_array($item)): ?>
                            <tr>
                                <td><?php echo strtoupper(substr($item['name'], 0, 4)); ?></td>
                                <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo "₹" . number_format($item['quantity'] * $item['price'], 2); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                        <td><?php echo "₹" . number_format($total_cost, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="btn-group">
                <a href="cart.php?empty_cart=true" class="btn">Empty Cart</a>
                <a href="products.php" class="btn">Continue Shopping</a>
                <!-- Confirm button with JS validation -->
                <button class="btn" id="confirmOrderBtn" data-total="<?php echo $total_cost; ?>">Confirm the Order</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById("confirmOrderBtn")?.addEventListener("click", function () {
            var total = parseFloat(this.getAttribute("data-total"));
            if (total < 100) {
                alert("⚠️ Minimum cart value should be ₹100 to place an order.");
            } else {
                window.location.href = "order_confirmation.php";
            }
        });
    </script>

</body>
</html>
