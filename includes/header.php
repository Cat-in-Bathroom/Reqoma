<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
$profile_picture = '';
if ($isLoggedIn) {
    require_once __DIR__ . '/../includes/config.php';
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $profile_picture = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reqoma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<?php $base_url = '/Reqoma/pages/'; ?>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand" href="<?= $isLoggedIn ? $base_url . 'dashboard.php' : $base_url . 'index.php' ?>">Reqoma</a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Full Navbar Collapse -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Centered and Large Search Bar -->
            <div class="mx-auto" style="width: 50%;">
                <form class="d-flex" role="search" action="<?= $base_url ?>search.php" method="GET">
                    <input class="form-control form-control-lg me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-outline-light btn-lg" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <!-- Right-side nav items -->
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center" href="<?= $base_url ?>profile.php">
                            <?php if (!empty($profile_picture)): ?>
                                <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                            <?php endif; ?>
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger ms-2" href="<?= $base_url ?>logout.php" style="color:white;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


