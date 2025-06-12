<script>
document.addEventListener('DOMContentLoaded', function() {
  const dashboardFlex = document.getElementById('dashboard-flex');
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');

  if (sidebarToggle && dashboardFlex && sidebar) {
    sidebarToggle.addEventListener('click', function() {
      const isVisible = sidebar.classList.contains('sidebar-visible');
      if (isVisible) {
        sidebar.classList.remove('sidebar-visible');
        sidebar.classList.add('sidebar-hidden');
        dashboardFlex.classList.add('hide-sidebar');
        sidebarToggle.setAttribute('aria-expanded', 'false');
      } else {
        sidebar.classList.remove('sidebar-hidden');
        sidebar.classList.add('sidebar-visible');
        dashboardFlex.classList.remove('hide-sidebar');
        sidebarToggle.setAttribute('aria-expanded', 'true');
      }
    });
  }
});
</script>