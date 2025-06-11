<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Update this query to use password_hash column name
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify against password_hash column
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

<div class="container-fluid">
    <div class="d-flex min-vh-100 align-items-center justify-content-center" id="dashboard-flex">
        <!-- Main content -->
        <main id="main-content" class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-4"> <!-- Responsive column width -->
                        <div class="card shadow-lg border-0"> <!-- Added border-0 -->
                            <div class="card-body p-5">
                                <h3 class="card-title text-center mb-4">Login</h3>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php endif; ?>
                                <form method="POST" action="">
                                    <div class="mb-4">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control form-control-lg" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control form-control-lg" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                                    <div class="text-center mt-3">
                                        <a href="register.php" class="text-muted">Need an account? Register</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>