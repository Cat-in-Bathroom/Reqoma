<?php $base = '/Reqoma'; ?>

<?php include '<?= $base ?>/includes/auth.php'; ?>
<?php include '<?= $base ?>/includes/header.php'; ?>



<div class="container py-5 text-center">
    <h1 class="mb-3">Welcome to Math Formula Hub</h1>
    <p class="lead">Explore, create, and solve mathematical formulas — categorized, rated, and moderated.</p>
    <?php if (!$isLoggedIn): ?>
        <a href="login.php" class="btn btn-outline-primary btn-lg me-2">Login</a>
        <a href="register.php" class="btn btn-primary btn-lg">Register</a>
    <?php else: ?>
        <a href="dashboard.php" class="btn btn-success btn-lg">Go to Dashboard</a>
    <?php endif; ?>
</div>

<?php include '<?= $base ?>/includes/footer.php'; ?>
