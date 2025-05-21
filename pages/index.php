<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>

<?php $base_url = '/Reqoma/pages/'; ?>

<div class="container py-5 text-center">
    <h1 class="mb-3">Welcome to Math Formula Hub</h1>
    <p class="lead">Explore, create, and solve mathematical formulas — categorized, rated, and moderated.</p>
    <?php if (!$isLoggedIn): ?>
        <a href="<?= $base_url ?>login.php" class="btn btn-outline-primary btn-lg me-2">Login</a>
        <a href="<?= $base_url ?>register.php" class="btn btn-primary btn-lg">Register</a>
    <?php else: ?>
        <a href="<?= $base_url ?>dashboard.php" class="btn btn-success btn-lg">Go to Dashboard</a>
    <?php endif; ?>
</div>

<div class="mt-4 text-center">
    <button id="shareBtn" class="btn btn-secondary">
        <i class="bi bi-share"></i> Share this site
    </button>
</div>

<script>
document.getElementById('shareBtn').onclick = function() {
    if (navigator.share) {
        navigator.share({
            title: 'Math Formula Hub',
            text: 'Check out Math Formula Hub!',
            url: window.location.origin + '/Reqoma/pages/index.php'
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.origin + '/Reqoma/pages/index.php');
        alert('Link copied to clipboard!');
    }
};
</script>

<?php include '../includes/footer.php'; ?>
