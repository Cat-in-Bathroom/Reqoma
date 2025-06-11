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
      <li class="nav-item"><a class="nav-link text-white" href="profile.php"><i class="bi bi-person"></i> My Profile</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="leaderboard.php"><i class="bi bi-trophy"></i> Leaderboard</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="submit_formula.php"><i class="bi bi-plus-circle"></i> Submit Formula</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="history.php"><i class="bi bi-clock-history"></i> History</a></li>
      <?php if (isset($is_moderator) && $is_moderator): ?>
      <li class="nav-item"><a class="nav-link text-white" href="moderate_formulas.php"><i class="bi bi-shield-check"></i> Moderate Formulas</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<!-- Floating show button (always outside the sidebar nav) -->
<button id="sidebarShow"
        class="btn btn-primary btn-sm sidebar-show-btn"
        type="button"
        aria-label="Show Sidebar"
        aria-controls="sidebar"
        aria-expanded="false"
        style="display:none;">
  <i class="bi bi-list"></i>
</button>
