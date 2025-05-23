<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, email, profile_picture FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $profile_picture = $user['profile_picture'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif ($_FILES['profile_picture']['size'] > $max_size) {
            $error = "File size must be under 2MB.";
        } else {
            $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $target = '../uploads/profile_' . $user_id . '.' . $ext;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
                $profile_picture = $target;
            } else {
                $error = "Upload failed.";
            }
        }
    }

    // Update user in DB
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, profile_picture = :profile_picture WHERE id = :id");
    $stmt->execute([
        ':username' => $new_username,
        ':email' => $new_email,
        ':profile_picture' => $profile_picture,
        ':id' => $user_id
    ]);

    $_SESSION['username'] = $new_username;
    $message = "Profile updated successfully!";
    // Refresh user data
    $user['username'] = $new_username;
    $user['email'] = $new_email;
    $user['profile_picture'] = $profile_picture;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Account Settings</h1>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture</label><br>
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" style="max-width:100px;max-height:100px;"><br>
            <?php endif; ?>
            <input type="file" name="profile_picture" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Back to Dashboard</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>