<?php
session_start();
include 'config.php';
include 'headerB.php';
$currency = "₹"; // Define currency symbol
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketMitra - Explore Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            padding-top: 80px;
        }
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-around;
            background-color: #fff;
            padding: 15px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .filters-container select {
            padding: 8px;
            font-size: 16px;
            width: 180px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }
        .product {
            text-align: center;
            border: 1px solid #ddd;
            padding: 15px;
            width: 220px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }
        .product {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product:hover {
    transform: scale(1.05);
    box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

        .product img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .expired {
            background: #ddd;
            opacity: 0.6;
            pointer-events: none;
        }
        .expired-label {
            position: absolute;
            top: 5px;
            left: 5px;
            background: red;
            color: white;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
        }
        /* Google Translate Dropdown */
 .translate-container {
            position: absolute;
            top: 20px;
            left: 30px;
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
        .translate-container select {
            padding: 5px;
            font-size: 14px;
        }
        .filter-label {
            font-size: 14px;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="filters-container">
        <h2>Explore Products</h2>
        
        <select id="filterType" onchange="filterProducts()">
            <option value="">All Types</option>
            <?php
            $typeResult = $conn->query("SELECT DISTINCT type FROM products");
            while ($row = $typeResult->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['type']) . '">' . htmlspecialchars($row['type']) . '</option>';
            }
            ?>
        </select>
        <select id="filterLocation" onchange="filterProducts()">
            <option value="">All Locations</option>
            <?php
            $locationResult = $conn->query("SELECT DISTINCT location FROM products");
            while ($row = $locationResult->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['location']) . '">' . htmlspecialchars($row['location']) . '</option>';
            }
            ?>
        </select>
        <select id="sortOptions" onchange="sortProducts()">
            <option value="">Sort By</option>
            <option value="nameAsc">A-Z</option>
            <option value="nameDesc">Z-A</option>
            <option value="priceAsc">Price: Low to High</option>
            <option value="priceDesc">Price: High to Low</option>
            <option value="expiryAsc">Expiry Date: Oldest to Newest</option>
            <option value="expiryDesc">Expiry Date: Newest to Oldest</option>
        </select>
        <label class="filter-label">
            <input type="checkbox" id="filterNotExpired" onchange="filterProducts()"> Show Only Not Expired
        </label>
    </div>

    <div class="product-container" id="productContainer">
        <?php
        $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
        $today = date('Y-m-d');
        
        while ($obj = $result->fetch_object()) {
            $expired = ($obj->expiry_date < $today) ? "expired" : "";
            echo '<div class="product ' . $expired . '" 
                  data-type="' . htmlspecialchars($obj->type) . '" 
                  data-location="' . htmlspecialchars($obj->location) . '" 
                  data-expiry="' . $obj->expiry_date . '"
                  data-name="' . htmlspecialchars($obj->product_name) . '"
                  data-price="' . $obj->price . '">' . "\n";

            if ($expired) {
                echo '<span class="expired-label">Expired</span>' . "\n";
            }

            echo '<a href="product_details.php?id=' . $obj->id . '" ' . ($expired ? 'onclick="return false;"' : '') . '>';
            echo '<img src="images/products/' . htmlspecialchars($obj->product_img_name) . '"/>';
            echo '</a>';
            echo '<h3>' . htmlspecialchars($obj->product_name) . '</h3>';
            echo '<p><strong>Type:</strong> ' . htmlspecialchars($obj->type) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($obj->location) . '</p>';
            echo '<p><strong>Quantity:</strong> ' . $obj->qty . ' Kg</p>';
            echo '<p><strong>Price:</strong> ' . $currency . $obj->price . ' / Kg</p>';
            echo '<p><strong>Manufactured:</strong> ' . $obj->manufactured_date . '</p>';
            echo '<p><strong>Expiry:</strong> ' . $obj->expiry_date . '</p>';
            echo '</div>' . "\n";
        }
        ?>
    </div>
</div>

<script>
   function filterProducts() {
    var typeFilter = document.getElementById("filterType").value.toLowerCase();
    var locationFilter = document.getElementById("filterLocation").value.toLowerCase();
    var showNotExpired = document.getElementById("filterNotExpired").checked;
    var today = new Date().toISOString().split('T')[0];

    var products = document.getElementsByClassName("product");

    for (var i = 0; i < products.length; i++) {
        var product = products[i];
        var productType = product.getAttribute("data-type").toLowerCase();
        var productLocation = product.getAttribute("data-location").toLowerCase();
        var productExpiry = product.getAttribute("data-expiry");

        var typeMatch = (typeFilter === "" || productType === typeFilter);
        var locationMatch = (locationFilter === "" || productLocation === locationFilter);
        var expiryMatch = (!showNotExpired || productExpiry >= today);

        if (typeMatch && locationMatch && expiryMatch) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    }
}

    function sortProducts() {
        var sortOption = document.getElementById("sortOptions").value;
        var products = Array.from(document.getElementsByClassName("product"));
        
        products.sort((a, b) => {
            if (sortOption === "nameAsc") return a.dataset.name.localeCompare(b.dataset.name);
            if (sortOption === "nameDesc") return b.dataset.name.localeCompare(a.dataset.name);
            if (sortOption === "priceAsc") return a.dataset.price - b.dataset.price;
            if (sortOption === "priceDesc") return b.dataset.price - a.dataset.price;
            if (sortOption === "expiryAsc") return new Date(a.dataset.expiry) - new Date(b.dataset.expiry);
            if (sortOption === "expiryDesc") return new Date(b.dataset.expiry) - new Date(a.dataset.expiry);
        });
        
        var container = document.getElementById("productContainer");
        container.innerHTML = "";
        products.forEach(product => container.appendChild(product));
    }
</script>
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
