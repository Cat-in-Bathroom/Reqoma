<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

// Fetch user data from DB
$stmt = $pdo->prepare("SELECT username, display_name, email, profile_picture FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
  <div class="d-flex" id="dashboard-flex">
    <!-- Sidebar -->
    <nav id="sidebar">...</nav>
    <!-- Main content -->
    <main id="main-content" class="flex-grow-1 px-md-4 py-4">
      <div class="container" style="max-width: 1000px;">
        <div class="mt-5">
            <h1>Your Profile</h1>
            <p><strong>Profile Name (private):</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Display Name (shown to others):</strong> <?= htmlspecialchars($user['display_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <?php if (!empty($user['profile_picture'])): ?>
                <p><strong>Profile Picture:</strong><br>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="rounded-circle" style="max-width:60px;max-height:60px;">
                </p>
            <?php endif; ?>
            <a href="settings.php" class="btn btn-secondary me-2">Settings</a>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
      </div>
    </main>
  </div>
</div>

<?php include '../includes/footer.php'; ?>