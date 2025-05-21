<?php include 'headerF.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketMitra - Contact</title>
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
        

        /* Page Content */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            padding-top: 80px;
        }

        .heading {
            font-size: 24px;
            color: #2E8B57;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .contact-info a {
            color: #2E8B57;
            text-decoration: none;
            font-weight: bold;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .contact-image img {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<!-- Google Translate Dropdown -->
<div class="translate-container">
        <div id="google_translate_element"></div>
    </div>
    <!-- Contact Content -->
    <div class="container">
        <h2 class="heading">Get in Touch</h2>
        <p class="contact-info">Have any questions? Feel free to reach out to us at <br>
        <a href="mailto:support@mm.com">support@mm.com</a></p>

        <div class="contact-image">
            <img src="images/contact_us.png" alt="Contact Us">
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
