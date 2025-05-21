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

<?php include '../includes/footer.php'; ?>
