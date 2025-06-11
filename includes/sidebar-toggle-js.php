<script>
document.addEventListener('DOMContentLoaded', function() {
  const dashboardFlex = document.getElementById('dashboard-flex');
  const sidebar = document.getElementById('sidebar');
  const sidebarHide = document.getElementById('sidebarHide');
  const sidebarShow = document.getElementById('sidebarShow');

  if (sidebarHide && sidebarShow && dashboardFlex && sidebar) {
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
  }
});
</script>