<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is a moderator or admin
$is_moderator = false;
if ($_SESSION['user_id']) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $role = $stmt->fetchColumn();
    if ($role === 'moderator' || $role === 'admin') {
        $is_moderator = true;
    }
}

// Fetch user's attempt history
$stmt = $pdo->prepare("
    SELECT 
        fa.attempt_date,
        fa.is_correct,
        f.title,
        f.formula_text,
        f.score
    FROM formula_attempts fa
    JOIN formulas f ON fa.formula_id = f.id
    WHERE fa.user_id = :user_id
    ORDER BY fa.attempt_date DESC
");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex" id="dashboard-flex">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main content -->
        <main id="main-content" class="flex-grow-1 px-md-4 py-4">
            <div class="container" style="max-width: 1000px;">
                <h2>Your Formula History</h2>
                
                <?php if (empty($attempts)): ?>
                    <div class="alert alert-info">
                        You haven't attempted any formulas yet.
                        <a href="dashboard.php" class="alert-link">Browse formulas</a> to get started!
                    </div>
                <?php else: ?>
                    <div id="formula-row" class="row g-4">
                        <?php foreach ($attempts as $attempt): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card formula-card h-100 <?= $attempt['is_correct'] ? 'border-success' : 'border-danger' ?>">
                                    <div class="card-body" style="height: 200px;">
                                        <h5 class="card-title"><?= htmlspecialchars($attempt['title']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($attempt['formula_text']) ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge <?= $attempt['is_correct'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $attempt['is_correct'] ? 'Correct' : 'Incorrect' ?>
                                            </span>
                                            <small class="text-muted">
                                                Score: <?= $attempt['score'] ?>
                                            </small>
                                        </div>
                                        <div class="text-muted mt-2">
                                            <small>Attempted: <?= date('M j, Y, g:i a', strtotime($attempt['attempt_date'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>