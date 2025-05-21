<?php include 'headerF.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketMitra - About</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
        /* Top Bar */
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

        /* Page Content */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            padding-top: 80px;
        }

        .section {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .text {
            flex: 1;
            padding: 20px;
            text-align: justify;
        }

        .text h2 {
            color: #2E8B57; /* Emerald Green */
            font-size: 24px;
            margin-bottom: 10px;
        }

        .image {
            flex: 1;
            text-align: center;
        }

        .image img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .full-width-image {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

   <!-- Google Translate Dropdown -->
<div class="translate-container">
        <div id="google_translate_element"></div>
    </div>

    <div class="container">
        <!-- The Problem Section -->
        <div class="section">
            <div class="text">
                <h2>The Problem</h2>
                <p>Many consumers struggle to find fresh and high-quality vegetables while local vegetable sellers and farmers face challenges in reaching a wider customer base. Limited access, lack of transparency, and reliance on intermediaries create barriers to connecting consumers directly with local produce.</p>
            </div>
            <div class="image">
                <img src="images/problem.png" alt="The Problem">
            </div>
        </div>

        <!-- The Solution Section -->
        <div class="section">
            <div class="image">
                <img src="images/solution.png" alt="The Solution">
            </div>
            <div class="text">
                <h2>The Solution</h2>
                <p>MarketMitra is an online platform that bridges the gap between local vegetable sellers, farmers, and consumers. It provides a convenient and transparent marketplace where consumers can easily access a wide variety of fresh and locally grown vegetables. By eliminating intermediaries, MarketMitra empowers local farmers and sellers to showcase their produce directly to customers, enabling a direct farm-to-table connection.</p>
            </div>
        </div>

        <!-- Full Width Image -->
        <div class="image">
            <img src="images/market.png" alt="MarketMitra Overview" class="full-width-image">
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
