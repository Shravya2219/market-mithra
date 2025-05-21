<?php
// Ensure no output or whitespace before this line

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


ob_start(); // Start output buffering to prevent header issues
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketMithra</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
    <style>
         /* Search Box */
         .search-form {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 5px;
            border-radius: 20px;
        }

        .search-box {
            border: none;
            outline: none;
            padding: 8px;
            width: 150px;
            border-radius: 20px;
            background: transparent;
        }

        .search-btn {
            border: none;
            background: none;
            cursor: pointer;
        }

        .search-btn img {
            width: 24px;
            height: 24px;
        }

        body { font-family: Arial, sans-serif; margin: 0; padding: 0; overflow-x: hidden; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; }
        .logo img { width: 70px; cursor: pointer; }
        .nav-icons { display: flex; gap: 20px; margin-right: 50px; }
        .nav-icons a img { width: 40px; height: 40px; cursor: pointer; }
        .menu-dropdown { display: none; position: absolute; top: 60px; right: 20px; background: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 5px; width: 170px; z-index: 1000; }
        .menu-dropdown ul { list-style: none; padding: 10px; margin: 0; }
        .menu-dropdown ul li { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        .menu-dropdown ul li a { text-decoration: none; color: #333; display: block; }
        .menu-dropdown ul li a:hover { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="homeF.php"><img src="images/icons/logo.png" alt="Logo"></a>
        </div>
        <div class="nav-icons">
            <!-- Search Bar moved near products logo -->
        
            
           
            <a href="about.php" style="text-decoration: none; color: #333; font-weight: 500;">About Us</a>
    <a href="#" id="change-language" style="text-decoration: none; color: #333; font-weight: 500;">Change Language</a>
    <a href="contact.php" style="text-decoration: none; color: #333; font-weight: 500;">Contact Us</a>
    <a href="logout.php" style="text-decoration: none; color: #333; font-weight: 500;">Log out</a>
    <a href="profile.php"><img src="images/icons/user.png" alt="User"></a>
        </div>
    </div>
    <div class="menu-dropdown" id="menu">
        <ul>
           
            <li><a href="recv_orders.php">Received Orders</a></li>
            <li><a href="aboutF.php">About Us</a></li>
            <li><a href="contactF.php">Contact Us</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </div>
    <script>
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
    applySavedLanguage();
};

        function toggleMenu() {
            var menu = document.getElementById("menu");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }
        document.addEventListener("click", function(event) {
            var menu = document.getElementById("menu");
            var menuIcon = document.querySelector(".nav-icons a img[alt='Menu']");
            if (menu.style.display === "block" && !menu.contains(event.target) && !menuIcon.contains(event.target)) {
                menu.style.display = "none";
            }
        });
    </script>
<?php ob_end_flush(); // End output buffering ?>
