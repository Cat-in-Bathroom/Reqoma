<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>
<?php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optional: Check if user just registered
if (!isset($_SESSION['registered']) || $_SESSION['registered'] !== true) {
    // Redirect to registration page if not coming from there
    header("Location: register.php");
    exit();
}

// Optional: Unset the registered flag
unset($_SESSION['registered']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .success-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .success-box h1 {
            color: #4CAF50;
        }
        .success-box a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: white;
            background: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .success-box a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h1>Registration Successful!</h1>
        <p>Your account has been created. You can now log in.</p>
        <a href="login.php">Go to Login</a>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
