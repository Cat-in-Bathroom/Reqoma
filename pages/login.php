<?php
session_start();

// Database connection
$host = "localhost";
$user = "your_db_user";
$password = "your_db_password";
$database = "your_db_name";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $user;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Simple Login Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
