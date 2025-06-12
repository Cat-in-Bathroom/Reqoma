<nav id="sidebar" class="sidebar sidebar-visible" role="navigation" aria-label="Sidebar Navigation">
  <ul class="nav flex-column mb-0">
    <!-- Toggle button as a nav item -->
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'dashboard.php') echo 'active'; ?>" href="dashboard.php" title="Home">
        <i class="bi bi-house"></i> <span class="sidebar-label">Home</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'profile.php') echo 'active'; ?>" href="profile.php" title="My Profile">
        <i class="bi bi-person"></i> <span class="sidebar-label">My Profile</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'settings.php') echo 'active'; ?>" href="settings.php" title="Settings">
        <i class="bi bi-gear"></i> <span class="sidebar-label">Settings</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'leaderboard.php') echo 'active'; ?>" href="leaderboard.php" title="Leaderboard">
        <i class="bi bi-trophy"></i> <span class="sidebar-label">Leaderboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'submit_formula.php') echo 'active'; ?>" href="submit_formula.php" title="Submit Formula">
        <i class="bi bi-plus-circle"></i> <span class="sidebar-label">Submit Formula</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'history.php') echo 'active'; ?>" href="history.php" title="History">
        <i class="bi bi-clock-history"></i> <span class="sidebar-label">History</span>
      </a>
    </li>
    <?php if (isset($is_moderator) && $is_moderator): ?>
    <li class="nav-item">
      <a class="nav-link text-white <?php if(basename($_SERVER['PHP_SELF']) === 'moderate_formulas.php') echo 'active'; ?>" href="moderate_formulas.php" title="Moderate Formulas">
        <i class="bi bi-shield-check"></i> <span class="sidebar-label">Moderate Formulas</span>
      </a>
    </li>
    <?php endif; ?>
  </ul>
</nav>

<button id="sidebarToggle" aria-label="Toggle Sidebar" aria-expanded="true" aria-controls="sidebar">
  <i class="fas fa-chevron-left"></i>
</button>
