<?php
session_start();
$shop_name = "MarketMithra";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $shop_name; ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }

    .logo img { width: 70px; }

    .nav-icons {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .nav-icons a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
    }

    

    .language-popup {
      display: none;
      position: fixed;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      z-index: 1100;
      text-align: center;
    }

    .language-popup select {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    section {
      padding: 100px 20px 60px;
      margin-top: 60px;
      min-height: 100vh;
    }

    #home { background: #f9f9f9; }
    #about { background: #eef; }
    #contact { background: #efe; }
    .slider {
  width: 100%;
  max-width: 1000px;
  margin: 20px auto;
  overflow: hidden;
  position: relative;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.slides {
  display: flex;
  transition: transform 0.5s ease-in-out;
}

.slides img {
  width: 100%;
  flex-shrink: 0;
  height: 700px;
  object-fit: cover;
}

.slide-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.5);
  color: white;
  border: none;
  padding: 10px;
  font-size: 24px;
  cursor: pointer;
  z-index: 1001;
}

.prev { left: 10px; }
.next { right: 10px; }

@keyframes slide {
  0% { transform: translateX(0%); }
  33% { transform: translateX(-100%); }
  66% { transform: translateX(-200%); }
  100% { transform: translateX(0%); }
}

    .language-popup button {
      padding: 8px 15px;
      background: green;
      color: white;
      border: none;
      cursor: pointer;
    }

    html {
      scroll-behavior: smooth;
    }

    ul {
      margin-left: 20px;
    }

    .icon img {
      vertical-align: middle;
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<!-- Google Translate (hidden) -->
<div id="google_translate_element" style="display: none;"></div>

<!-- Navbar -->
<div class="navbar">
  <div class="logo">
    <a href="#home"><img src="images/icons/logo.png" alt="Logo" /></a>
  </div>
  <div class="nav-icons">
    <a href="#about">About Us</a>
    <a href="#" id="change-language">Change Language</a>
    <a href="#contact">Contact Us</a>
    <a href="login.php"><img src="images/icons/user.png" alt="User" style="width: 32px; height: 32px;" /></a>
  </div>
</div>

<div class="slider">
  <div class="slides" id="slide-track">
    <img src="images/slide1.webp" alt="Slide 1" />
    <img src="images/slide2.png" alt="Slide 2" />
    <img src="images/slide3.png" alt="Slide 3" />
  </div>
  <button class="slide-btn prev" onclick="prevSlide()">❮</button>
  <button class="slide-btn next" onclick="nextSlide()">❯</button>
</div>


<section id="about">
  <h2>About Us</h2>
  <p><strong>MarketMithra</strong> is an online platform dedicated to revolutionizing the agricultural marketplace by eliminating intermediaries and ensuring farmers receive fair prices for their produce.</p>
  <p>Our mission is to:</p>
  <ul>
    <li>Empower farmers by connecting them directly with consumers.</li>
    <li>Enhance transparency and trust in agricultural trade.</li>
    <li>Promote sustainable and profitable farming practices.</li>
  </ul>
  <p>We believe in the power of technology to bridge the gap between farmers and buyers, creating a win-win situation for everyone involved in the agricultural ecosystem.</p>
</section>

<section id="contact">
  <h2>Contact Us</h2>
  <p>If you have any questions, feedback, or need assistance, feel free to reach out to us.</p>
  <p><span class="icon"><img src="images/icons/mail.png" /></span>Email: <a href="mailto:marketmithra@gmail.com">marketmithra@gmail.com</a></p>
  <p><span class="icon"><img src="images/icons/phone.png" /></span>Phone: +91-9876543210</p>
  <p><span class="icon"><img src="images/icons/location.png" /></span>Address:Building No. 1A and 1B,
Raheja Mindspace,
HUDA Techno Enclave, HITEC City,
Madhapur, Hyderabad - 500086,
Telangana, India.</p>
</section>

<!-- Language Popup -->
<div class="language-popup" id="language-popup">
  <select id="language-select">
    <option value="en">English</option>
    <option value="hi">Hindi</option>
    <option value="te">Telugu</option>
    <option value="ta">Tamil</option>
    <option value="kn">Kannada</option>
    <option value="mr">Marathi</option>
    <option value="bn">Bengali</option>
  </select>
  <button id="translate-btn">Translate</button>
</div>

<script>
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({ 
      pageLanguage: 'en',
      includedLanguages: 'en,hi,te,ta,kn,mr,bn',
      autoDisplay: false
    }, 'google_translate_element');
  }

  (function() {
    var gtScript = document.createElement("script");
    gtScript.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    gtScript.async = true;
    document.body.appendChild(gtScript);
  })();

  document.getElementById("change-language").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("language-popup").style.display = "block";
  });

  document.getElementById("translate-btn").addEventListener("click", function() {
    var language = document.getElementById("language-select").value;
    localStorage.setItem("selectedLanguage", language);
    var frame = document.querySelector(".goog-te-combo");
    if (frame) {
      frame.value = language;
      frame.dispatchEvent(new Event("change"));
    }
    document.getElementById("language-popup").style.display = "none";
  });
  
</script>
<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll("#slide-track img");

  function updateSlide() {
    const track = document.getElementById("slide-track");
    track.style.transform = `translateX(-${currentSlide * 100}%)`;
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlide();
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    updateSlide();
  }
</script>

</body>
</html>
