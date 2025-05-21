<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Your Profile</h1>
    <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
    <!-- Add more profile info here if needed -->
    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>