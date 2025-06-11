<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$is_moderator = false;
if ($_SESSION['user_id']) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $role = $stmt->fetchColumn();
    if ($role === 'moderator' || $role === 'admin') {
        $is_moderator = true;
    }
}

// Fetch formulas (only approved/public ones)
$formulas_stmt = $pdo->query("SELECT id, title, formula_text, score FROM formulas WHERE status = 'approved' ORDER BY created_at DESC");
$formulas = $formulas_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

  <div class="container-fluid">
    <div class="d-flex" id="dashboard-flex">
      <!-- Sidebar -->
      <nav id="sidebar"
           class="sidebar sidebar-visible p-3 d-flex flex-column"
           aria-label="Sidebar Navigation">
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
        <div class="sidebar-content flex-grow-1">
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
      <!-- Sidebar Show Button (floating, always accessible) -->
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
        <h2>Welcome to the Dashboard</h2>
        <div id="formula-row" class="row justify-content-center">
          <!-- Cards will be loaded here -->
        </div>
        <div id="loading" class="text-center my-3" style="display:none;">
          <div class="spinner-border text-primary"></div>
        </div>
      </main>
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
    </div>
  </div>

<?php include '../includes/footer.php'; ?>