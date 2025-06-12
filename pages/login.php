<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found.";
    }
}

include '../includes/auth.php';
include '../includes/header.php';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
  <main id="main-content" class="w-100" style="max-width: 400px;">
    <form method="POST" action=""
          style="background:#fff; border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,0.08); padding:2.5rem;">
      <h3 class="text-center mb-4">Login</h3>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" type="text" name="username" class="form-control" required autofocus
               value="<?= htmlspecialchars($username ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <div class="text-center mt-3">
        <a href="register.php" class="text-muted">Need an account? Register</a>
      </div>
    </form>
  </main>
</div>


<?php include '../includes/footer.php'; ?>