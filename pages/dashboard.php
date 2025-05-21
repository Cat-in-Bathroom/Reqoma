<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #343a40;
      color: white;
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
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
        <h4 class="text-white mb-4">Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a class="nav-link" href="profile.php">My Profile</a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link" href="settings.php">Setting</a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link" href="page3.php">Page 3</a>
          </li>
        </ul>
      </nav>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <h2>Welcome to the Dashboard</h2>
        <p>Select a page from the sidebar to navigate.</p>
      </main>
    </div>
  </div>

<?php include '../includes/footer.php'; ?>