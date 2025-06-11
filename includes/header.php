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
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time(); ?>">
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
    }
    #dashboard-flex {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      position: fixed;
      top: 56px; /* Height of the navbar (default Bootstrap is 56px) */
      left: 0;
      width: 260px;
      min-width: 200px;
      max-width: 300px;
      height: calc(100vh - 56px);
      transition: margin-left 0.3s cubic-bezier(.4,2,.6,1), opacity 0.3s;
      background-color: #343a40;
      color: white;
      z-index: 1050;
      overflow-y: auto;
    }
    .sidebar-hidden {
      margin-left: -260px;
      opacity: 0;
      pointer-events: none;
    }
    .sidebar-visible {
      margin-left: 0;
      opacity: 1;
    }
    #main-content {
      flex-grow: 1;
      margin-left: 260px; /* Same as sidebar width */
      margin-top: 56px;   /* Same as navbar height */
      transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
    }
    .sidebar-hidden ~ #main-content {
      margin-left: 0;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
      display: block;
      padding-left: 10px;
    }
    .card-link {
      color: inherit;
      text-decoration: none;
    }
    .card-link:hover {
      text-decoration: underline;
    }
    .sidebar-show-btn {
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 1051;
      display: none;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 80vw;
        min-width: 0;
        max-width: 100vw;
        position: fixed;
        top: 56px;
        left: 0;
        height: calc(100vh - 56px);
        z-index: 1050;
      }
      #main-content {
        margin-left: 0 !important;
      }
    }
    .navbar {
      padding-top: 0 !important;
      padding-bottom: 0 !important;
      min-height: 56px;
    }
  </style>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

<?php $base_url = '/Reqoma/pages/'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $isLoggedIn ? $base_url . 'dashboard.php' : $base_url . 'index.php' ?>">Reqoma</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
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
                        <a class="nav-link" href="<?= $base_url ?>leaderboard.php">
                            <i class="bi bi-trophy"></i> Leaderboard
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
