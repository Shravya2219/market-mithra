<?php
session_start();
include 'config.php'; // Database connection file
include 'headerB.php';
$currency = "₹"; // Define currency symbol

// Fetch product details based on the product ID
$product_id = $_GET['id']; // Get the product ID from the URL
$query = "SELECT p.*, u.name as farmer_name, u.id as farmer_id 
FROM products p 
INNER JOIN users u ON p.farmer_id = u.id 
WHERE p.id = ?";

// Check if the query is prepared correctly
$stmt = $conn->prepare($query);

if ($stmt === false) {
    // If there is an issue with preparing the query, output the error
    die('Error preparing query: ' . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_object();
} else {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details | MarketMitra</title>
    <style>
        /* Add your custom styles */
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
        .menu-icon {
            font-size: 30px;
            cursor: pointer;
        }
        .menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 10px;
            background: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        .menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .menu ul li {
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .menu ul li:last-child {
            border-bottom: none;
        }
        .menu ul li a {
            text-decoration: none;
            color: black;
            display: block;
        }
        .product-detail-container {
            display: flex;
            padding: 20px;
            padding-top: 80px;
        }
        .product-image {
            width: 300px;
            height: 300px;
            object-fit: cover;
        }
        .product-info {
            margin-left: 20px;
        }
        .product-info h3 {
            font-size: 2em;
        }
        .product-info p {
            font-size: 1.2em;
        }
        .farmer-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        /* Google Translate Dropdown */
 .translate-container {
            position: absolute;
            top: 20px;
            left: 30px;
        }

        .translate-container select {
            padding: 5px;
            font-size: 14px;
        }
        .farmer-link:hover {
            text-decoration: underline;
        }

        /* Add to Cart Box Styles */
        .add-to-cart-box {
            margin-top: 20px;
            padding: 10px;
           
            border-radius: 5px;
            
        }

        .add-to-cart-btn {
            padding: 10px 20px;
            background-color: #007bff;
            
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

   

    <!-- Product Details Section -->
    <div class="product-detail-container">
        <!-- Product Image -->
        <div>
            <img src="images/products/<?php echo htmlspecialchars($product->product_img_name, ENT_QUOTES, 'UTF-8'); ?>" class="product-image" alt="<?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <h3><?php echo htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><strong>Price:</strong> <?php echo $currency . $product->price; ?> / Kg</p>
            <p><strong>Farmer:</strong> <a href="farmer_profile.php?id=<?php echo $product->farmer_id; ?>" class="farmer-link"><?php echo htmlspecialchars($product->farmer_name, ENT_QUOTES, 'UTF-8'); ?></a></p>
            <p><strong>Description:</strong> <?php echo isset($product->product_desc) ? htmlspecialchars($product->product_desc, ENT_QUOTES, 'UTF-8') : 'No description available.'; ?></p>

            <!-- Add to Cart Box -->
            <div class="add-to-cart-box">
                <form action="cart.php" method="GET">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

<!-- Google Translate Script -->
<script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'hi,te,ta,kn,ml,gu,pa,mr,bn,ur,as,or,sd,ne,si,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }

        function applySavedLanguage() {
            var savedLanguage = localStorage.getItem("selectedLanguage");
            if (savedLanguage) {
                var googleFrame = document.querySelector(".goog-te-combo");
                if (googleFrame) {
                    googleFrame.value = savedLanguage;
                    googleFrame.dispatchEvent(new Event("change"));
                }
            }
        }

        // Wait for Google Translate to load and apply language
        window.onload = function() {
            setTimeout(applySavedLanguage, 1000);
        };
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>


<?php
$conn->close();
?>
