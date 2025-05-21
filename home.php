<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $shop_name; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; overflow-x: hidden; }
        .hero { position: relative; width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .hero img { width: 100%; height: 100%; object-fit: cover; }
        .hero-text { position: absolute; left: -100%; top: 50%; transform: translateY(-50%); color: white; font-size: 24px; font-weight: bold; max-width: 600px; white-space: nowrap; }
        .buttons { margin-top: 20px; }
        .btn { padding: 10px 20px; background: green; color: white; border: none; cursor: pointer; margin: 5px; text-decoration: none; }
    </style>
</head>

</html>
