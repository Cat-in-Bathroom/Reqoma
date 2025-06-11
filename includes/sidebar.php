<nav id="sidebar" class="sidebar sidebar-visible p-3" role="navigation" aria-label="Sidebar Navigation">
  <hr class="bg-secondary my-2">
  <div class="sidebar-content">
    <ul class="nav nav-pills flex-column gap-1">
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'dashboard.php') echo 'active'; ?>" href="dashboard.php">
          <i class="bi bi-house"></i> <span class="sidebar-label">Home</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'profile.php') echo 'active'; ?>" href="profile.php">
          <i class="bi bi-person"></i> <span class="sidebar-label">My Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'settings.php') echo 'active'; ?>" href="settings.php">
          <i class="bi bi-gear"></i> <span class="sidebar-label">Settings</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'leaderboard.php') echo 'active'; ?>" href="leaderboard.php">
          <i class="bi bi-trophy"></i> <span class="sidebar-label">Leaderboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'submit_formula.php') echo 'active'; ?>" href="submit_formula.php">
          <i class="bi bi-plus-circle"></i> <span class="sidebar-label">Submit Formula</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'history.php') echo 'active'; ?>" href="history.php">
          <i class="bi bi-clock-history"></i> <span class="sidebar-label">History</span>
        </a>
      </li>
      <?php if (isset($is_moderator) && $is_moderator): ?>
      <li class="nav-item">
        <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'moderate_formulas.php') echo 'active'; ?>" href="moderate_formulas.php">
          <i class="bi bi-shield-check"></i> <span class="sidebar-label">Moderate Formulas</span>
        </a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<!-- Single toggle button, always after sidebar -->
<button id="sidebarToggle"
        class="sidebar-toggle-btn"
        type="button"
        aria-label="Toggle Sidebar"
        aria-controls="sidebar"
        aria-expanded="true">
  <i class="bi bi-chevron-left"></i>
</button>
