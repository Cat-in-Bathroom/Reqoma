<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, display_name, email, profile_picture, profile_public FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_display_name = trim($_POST['display_name']);
    $new_email = trim($_POST['email']);
    $profile_picture = $user['profile_picture'];
    $profile_public = isset($_POST['profile_public']) ? 1 : 0;

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
            if (!is_writable(dirname($target))) {
                $error = "Uploads directory is not writable: " . dirname($target);
            } elseif (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
                $profile_picture = $target;
            } else {
                $error = "Upload failed. Check that the uploads directory exists and is writable. Target: $target";
            }
        }
    }

    // Only update if there are no errors
    if (empty($error)) {
        $stmt = $pdo->prepare("UPDATE users SET display_name = :display_name, email = :email, profile_picture = :profile_picture, profile_public = :profile_public WHERE id = :id");
        $stmt->execute([
            ':display_name' => $new_display_name,
            ':email' => $new_email,
            ':profile_picture' => $profile_picture,
            ':profile_public' => $profile_public,
            ':id' => $user_id
        ]);

        $message = "Profile updated successfully!";
        // Refresh user data
        $user['display_name'] = $new_display_name;
        $user['email'] = $new_email;
        $user['profile_picture'] = $profile_picture;
        $user['profile_public'] = $profile_public;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Account Settings</h1>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Profile Name (private)</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Display Name (shown to others)</label>
            <input type="text" name="display_name" class="form-control" value="<?= htmlspecialchars($user['display_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture</label><br>
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="rounded-circle" style="max-width:60px;max-height:60px;"><br>
            <?php endif; ?>
            <input type="file" name="profile_picture" class="form-control">
            <small class="form-text text-muted">
                Allowed file types: JPG, PNG, GIF. Max size: 2MB.
            </small>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="profile_public" name="profile_public" value="1" <?= !empty($user['profile_public']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="profile_public">Make my profile public</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Back to Dashboard</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>