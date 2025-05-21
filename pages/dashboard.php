<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>This is your dashboard.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<?php include '../includes/footer.php'; ?>