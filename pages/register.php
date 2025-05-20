<?php

require_once '../includes/config.php';

session_start();

$errors = [];
$username = '';
$email = '';

// Handle form submission

require_once '../includes/config.php';  // This gives you $pdo

// Example user data from form (validate and sanitize this in real use!)
$username = 'exampleUser';
$email = 'user@example.com';
$password = password_hash('userpassword', PASSWORD_DEFAULT); // Always hash passwords!

// Prepare an insert statement
$sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
$stmt = $pdo->prepare($sql);

// Bind values and execute
$stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => $password,
]);

echo "User registered successfully!";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Create an Account</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($username) ?>" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password (min. 6 chars)</label>
        <input type="password" name="password" class="form-control" id="password" required>
      </div>

      <div class="mb-3">
        <label for="confirm" class="form-label">Confirm Password</label>
        <input type="password" name="confirm" class="form-control" id="confirm" required>
      </div>

      <button type="submit" class="btn btn-primary">Register</button>
      <a href="login.php" class="btn btn-link">Already have an account?</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
