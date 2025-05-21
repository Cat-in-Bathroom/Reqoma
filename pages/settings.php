
<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Account Settings</h1>
    <p>Here you can update your account settings. (Feature coming soon!)</p>
    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>