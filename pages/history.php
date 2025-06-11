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
        <nav id="sidebar" class="sidebar sidebar-visible p-3" role="navigation" aria-label="Sidebar Navigation">
            <div class="sidebar-header d-flex justify-content-between align-items-center">
                <h4 class="text-white mb-0 fs-5">Dashboard</h4>
                <button id="sidebarHide" 
                        class="btn btn-outline-light btn-sm"
                        type="button"
                        aria-label="Hide Sidebar"
                        aria-controls="sidebar"
                        aria-expanded="true">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <hr class="bg-secondary my-2">
            <div class="sidebar-content">
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profile.php">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="settings.php">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="leaderboard.php">
                            <i class="bi bi-trophy"></i> Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="submit_formula.php">
                            <i class="bi bi-plus-circle"></i> Submit Formula
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="history.php">
                            <i class="bi bi-clock-history"></i> History
                        </a>
                    </li>
                    <?php if ($is_moderator): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="moderate_formulas.php">
                            <i class="bi bi-shield-check"></i> Moderate Formulas
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Floating show button -->
        <button id="sidebarShow"
                class="btn btn-primary btn-sm sidebar-show-btn"
                type="button"
                aria-label="Show Sidebar"
                aria-controls="sidebar"
                aria-expanded="false"
                style="display:none;">
            <i class="bi bi-list"></i>
        </button>

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
                    <div class="row g-4">
                        <?php foreach ($attempts as $attempt): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 <?= $attempt['is_correct'] ? 'border-success' : 'border-danger' ?>">
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

<!-- Add the sidebar toggle JavaScript -->
<script>
const dashboardFlex = document.getElementById('dashboard-flex');
const sidebar = document.getElementById('sidebar');
const sidebarHide = document.getElementById('sidebarHide');
const sidebarShow = document.getElementById('sidebarShow');

sidebarHide.addEventListener('click', function() {
    sidebar.classList.remove('sidebar-visible');
    sidebar.classList.add('sidebar-hidden');
    sidebarShow.style.display = 'block';
    dashboardFlex.classList.add('hide-sidebar');
    sidebarHide.setAttribute('aria-expanded', 'false');
    sidebarShow.setAttribute('aria-expanded', 'true');
});

sidebarShow.addEventListener('click', function() {
    sidebar.classList.remove('sidebar-hidden');
    sidebar.classList.add('sidebar-visible');
    sidebarShow.style.display = 'none';
    dashboardFlex.classList.remove('hide-sidebar');
    sidebarHide.setAttribute('aria-expanded', 'true');
    sidebarShow.setAttribute('aria-expanded', 'false');
});
</script>

<?php include '../includes/footer.php'; ?>