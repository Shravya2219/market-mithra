<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
$path = __DIR__ . "/config.php"; 
if (!file_exists($path)) {
    die("Database connection file not found.");
}
include_once($path);

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim and sanitize input data
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);  // Role: Farmer or Buyer

    // Prepare query
    $sql = "SELECT id, password FROM users WHERE email = ? AND user_type = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify password (If passwords are not hashed, use direct comparison)
        if (password_verify($password, $hashed_password) || $password === $hashed_password) {  
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = $role;  // Ensure consistency (use 'user_type' everywhere)

            // Redirect based on role
            if ($role === "farmer") {
                header("Location: homeF.php");
                exit();
            } elseif ($role === "buyer") {
                header("Location: homeB.php");
                exit();
            }
        } else {
            echo "<script>
                    alert('Invalid password!');
                    window.location.href = 'home.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Invalid credentials or role!');
                window.location.href = 'home.php';
              </script>";
        exit();
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
