<nav id="sidebar" class="sidebar sidebar-visible p-3" role="navigation" aria-label="Sidebar Navigation">
  <div class="sidebar-header d-flex justify-content-between align-items-center">
    <h4 class="text-white mb-0 fs-5">Dashboard</h4>
    <button id="sidebarHide" class="btn btn-outline-light btn-sm" type="button" aria-label="Hide Sidebar" aria-controls="sidebar" aria-expanded="true">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
  <hr class="bg-secondary my-2">
  <div class="sidebar-content">
    <ul class="nav nav-pills flex-column gap-1">
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'dashboard.php') echo 'active'; ?>" href="dashboard.php">
          <i class="bi bi-house"></i> Home
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'profile.php') echo 'active'; ?>" href="profile.php">
          <i class="bi bi-person"></i> My Profile
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'settings.php') echo 'active'; ?>" href="settings.php">
          <i class="bi bi-gear"></i> Settings
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'leaderboard.php') echo 'active'; ?>" href="leaderboard.php">
          <i class="bi bi-trophy"></i> Leaderboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'submit_formula.php') echo 'active'; ?>" href="submit_formula.php">
          <i class="bi bi-plus-circle"></i> Submit Formula
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'history.php') echo 'active'; ?>" href="history.php">
          <i class="bi bi-clock-history"></i> History
        </a>
      </li>
      <?php if (isset($is_moderator) && $is_moderator): ?>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'moderate_formulas.php') echo 'active'; ?>" href="moderate_formulas.php">
          <i class="bi bi-shield-check"></i> Moderate Formulas
        </a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<!-- Show button: always after sidebar, never inside -->
<button id="sidebarShow"
        class="sidebar-show-btn"
        type="button"
        aria-label="Show Sidebar"
        aria-controls="sidebar"
        aria-expanded="false">
  <i class="bi bi-list"></i>
</button>
