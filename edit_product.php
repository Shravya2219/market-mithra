<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // DB connection
include 'headerF.php';

$message = "";

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = $_GET['id'];

// Fetch product data
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Handle POST (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $manufactured_date = $_POST['manufactured_date'];
    $expiry_date = $_POST['expiry_date'];
    $farmer_id = $_POST['farmer_id'];
    $image_name = $product['product_img_name']; // Default to existing image

    // Check if a new image is uploaded
    if (!empty($_FILES["product_img"]["name"])) {
        $target_dir = "images/products/";
        $new_image_name = basename($_FILES["product_img"]["name"]);
        $target_file = $target_dir . $new_image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["product_img"]["tmp_name"]);
        if ($check === false) {
            $message = "❌ File is not an image.";
        } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $message = "❌ Only JPG, JPEG, PNG & GIF allowed.";
        } elseif (!move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file)) {
            $message = "❌ Failed to upload image.";
        } else {
            $image_name = $new_image_name; // Use new image
        }
    }

    if (empty($message)) {
        // Update query
        $sql = "UPDATE products SET 
                    type='$type',
                    product_name='$product_name',
                    product_desc='$product_desc',
                    product_img_name='$image_name',
                    qty='$qty',
                    price='$price',
                    location='$location',
                    manufactured_date='$manufactured_date',
                    expiry_date='$expiry_date',
                    farmer_id='$farmer_id'
                WHERE product_id=$product_id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('✅ Product updated successfully!');
                    window.location.href = 'homeF.php';
                  </script>";
            exit;
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!-- HTML PART -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/landing_bg.webp') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin-top: 80px;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 12px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        img {
            max-width: 100px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>
    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Type:</label>
            <select name="type" required>
                <option value="Root" <?= $product['type'] == 'Root' ? 'selected' : '' ?>>Root</option>
                <option value="Vegetable" <?= $product['type'] == 'Vegetable' ? 'selected' : '' ?>>Vegetable</option>
                <option value="Fruit" <?= $product['type'] == 'Fruit' ? 'selected' : '' ?>>Fruit</option>
                <option value="Pulses" <?= $product['type'] == 'Pulses' ? 'selected' : '' ?>>Pulses</option>
            </select>
        </div>

        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="product_desc" required><?= htmlspecialchars($product['product_desc']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="qty" value="<?= $product['qty'] ?>" required>
        </div>

        <div class="form-group">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
        </div>

        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($product['location']) ?>" required>
        </div>

        <div class="form-group">
            <label>Manufactured Date:</label>
            <input type="date" name="manufactured_date" value="<?= $product['manufactured_date'] ?>" required>
        </div>

        <div class="form-group">
            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" value="<?= $product['expiry_date'] ?>" required>
        </div>

        <div class="form-group">
            <label>Farmer ID:</label>
            <input type="number" name="farmer_id" value="<?= $product['farmer_id'] ?>" required>
        </div>

        <div class="form-group">
            <label>Current Image:</label><br>
            <img src="images/products/<?= $product['product_img_name'] ?>" alt="Current Image">
        </div>

        <div class="form-group">
            <label>Upload New Image (optional):</label>
            <input type="file" name="product_img">
        </div>

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
