<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>

<footer class="bg-dark fixed-bottom text-light py-3">
  <div class="container text-center">
    <div class="mb-2 d-flex justify-content-center align-items-center">
      <!-- Social links and share button in a flex row -->
      <div class="d-flex align-items-center">
        <div>
          <a href="https://discord.gg/SHqa3SxGHt" target="_blank" class="text-light me-3" aria-label="Discord">
            <i class="bi bi-discord"></i> Discord
          </a>
          <a href="https://github.com/Cat-in-Bathroom/Reqoma" target="_blank" class="text-light me-3" aria-label="GitHub">
            <i class="bi bi-github"></i> GitHub
          </a>
          <a href="https://www.patreon.com/c/Cat_in_Bathroom889" target="_blank" class="text-light me-3" aria-label="Patreon">
            <i class="bi bi-patreon"></i> Patreon
          </a>
        </div>
        <?php if ($isLoggedIn): ?>
          <button id="shareBtn" class="btn btn-secondary ms-4">
            <i class="bi bi-share"></i> Share Reqoma
          </button>
        <?php endif; ?>
      </div>
    </div>
    <small>&copy; 2025 Cat in Bathroom. Built with ChatGPT</small>
  </div>
</footer>

<?php if ($isLoggedIn): ?>
<script>
document.getElementById('shareBtn').onclick = function() {
    if (navigator.share) {
        navigator.share({
            title: 'Math Formula Hub',
            text: 'Check out Math Formula Hub!',
            url: window.location.origin + '/Reqoma/pages/index.php'
        });
    } else {
        navigator.clipboard.writeText(window.location.origin + '/Reqoma/pages/index.php');
        alert('Link copied to clipboard!');
    }
};
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/sidebar-toggle-js.php'; ?>
</body>
</html>
