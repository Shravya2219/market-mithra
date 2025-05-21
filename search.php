<?php
session_start();
include 'config.php'; // Ensure database connection
include 'headerB.php';

if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);

    // Check if connection is established
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    // Prepare SQL query using the correct column name: product_name
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    
    // If prepare fails, show error
    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $search_term = "%$search_query%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Search Results for '$search_query'</h2>";

    if ($result->num_rows > 0) {
        echo "<div class='product-container'>"; // Container for grid layout
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            // Ensure correct path for image display
            echo "<img src='images/products/" . $row['product_img_name'] . "' alt='" . $row['product_name'] . "' class='product-img'>";
            echo "<h3>" . $row['product_name'] . "</h3>";
            echo "<p class='price'>Price: ₹" . $row['price'] . "</p>";
            echo "<p>" . $row['product_desc'] . "</p>";
            echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
            echo "<a href='product_details.php?id=" . $row['id'] . "' class='view-details'>View Details</a>";
            echo "</div>";
        }
        echo "</div>"; // Close container
    } else {
        echo "<p class='error-message'>The required product is not available. Redirecting to home...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'homeB.php';
                }, 3000); // Redirect after 3 seconds
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<style>
/* CSS Styling */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f8f8;
    text-align: center;
}
h2 {
    color: #333;
    margin-top: 100px; /* Adjust this value as needed */
}


.product-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

.product-card {
    width: 250px;
    background: white;
    padding: 15px;
   
    border-radius: 10px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.product-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
}

.price {
    font-weight: bold;
    color: green;
}

.view-details {
    display: inline-block;
    padding: 8px 12px;
    margin-top: 10px;
    text-decoration: none;
    color: white;
    background: #28a745;
    border-radius: 5px;
    transition: 0.3s;
}

.view-details:hover {
    background: #218838;
}

.error-message {
    color: red;
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
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
</style>
<!-- Google Translate Dropdown -->
<div class="translate-container">
        <div id="google_translate_element"></div>
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
