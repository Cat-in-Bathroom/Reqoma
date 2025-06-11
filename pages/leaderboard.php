<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';
include '../includes/header.php';

// Fetch top users (adjust 'score' to your actual column)
$stmt = $pdo->query("SELECT username, score FROM users ORDER BY score DESC LIMIT 10");
$users = $stmt->fetchAll();
?>

<div class="container-fluid">
  <div class="d-flex" id="dashboard-flex">
    <!-- Sidebar -->
    <nav id="sidebar">
      <?php include '../includes/sidebar.php'; ?>
    </nav>
    <!-- Main content -->
    <main id="main-content" class="flex-grow-1 px-md-4 py-4">
      <div class="container" style="max-width: 1000px;">
        <div class="mt-5">
            <h1>Leaderboard</h1>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $user): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['score']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
      </div>
    </main>
  </div>
</div>

<?php include '../includes/footer.php'; ?>