<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketMitra - Manage Products</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            padding-top: 120px;
        }
        .product {
            text-align: center;
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
            position: relative;
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
        .product img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
        .delete-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
<!-- Google Translate Dropdown -->
<div class="translate-container">
        <div id="google_translate_element"></div>
    </div>
<div class="product-container">
    <?php
    session_start();
    include 'config.php';
    include 'headerF.php';
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
        die("Access Denied. Please log in as a farmer.");
    }

    $farmer_id = $_SESSION['user_id'];

    if ($stmt = $conn->prepare("SELECT * FROM products WHERE farmer_id = ? ORDER BY id ASC")) {
        $stmt->bind_param("i", $farmer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($obj = $result->fetch_object()) {
                echo '<div class="product" id="product-' . $obj->id . '">';
                echo '<img src="images/products/' . htmlspecialchars($obj->product_img_name) . '"/>';
                echo '<h3>' . htmlspecialchars($obj->product_name) . '</h3>';
                echo '<p><strong>Units Available:</strong> ' . htmlspecialchars($obj->qty) . '</p>';
                echo '<button class="delete-button" data-id="' . $obj->id . '">Delete this</button>';
                echo '</div>';
            }
        } else {
            echo "<p>No products found.</p>";
        }
        $stmt->close();
    }
    ?>
</div>

<script>
$(document).ready(function() {
    $(".delete-button").click(function() {
        var productId = $(this).data("id");
        var productDiv = $("#product-" + productId);

        $.ajax({
            url: "delete_product_ajax.php",
            type: "POST",
            data: { id: productId },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    productDiv.fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("An error occurred while deleting the product.");
            }
        });
    });
});
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
