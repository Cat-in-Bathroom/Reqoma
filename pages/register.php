<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>

<?php

require_once __DIR__ . '/../includes/config.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab and sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // Basic validation
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email or username already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);

        if ($stmt->fetch()) {
            $errors[] = "Username or email already taken.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        // Redirect or notify success
        $_SESSION['registered'] = true;
        header("Location: successful_register.php");
        exit;
    }
}
?>

<?php $base_url = '/Reqoma/pages/'; ?>

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
        <label for="display_name" class="form-label">Display Name (shown to others)</label>
        <input type="text" name="display_name" class="form-control" id="display_name" required>
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

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="profile_public" name="profile_public" value="1" checked>
        <label class="form-check-label" for="profile_public">Make my profile public</label>
      </div>

      <button type="submit" class="btn btn-primary">Register</button>
      <a href="<?= $base_url ?>login.php" class="btn btn-link">Already have an account?</a>
    </form>
  </div>


<?php include '../includes/footer.php'; ?>