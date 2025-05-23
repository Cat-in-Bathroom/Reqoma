<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$is_moderator = false;
if ($_SESSION['user_id']) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $role = $stmt->fetchColumn();
    if ($role === 'moderator' || $role === 'admin') {
        $is_moderator = true;
    }
}

// Fetch formulas (only approved/public ones)
$formulas_stmt = $pdo->query("SELECT id, title, formula_text, score FROM formulas WHERE status = 'approved' ORDER BY created_at DESC");
$formulas = $formulas_stmt->fetchAll(PDO::FETCH_ASSOC);
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
    .card-link {
      color: inherit;
      text-decoration: none;
    }
    .card-link:hover {
      text-decoration: underline;
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
            <a class="nav-link" href="leaderboard.php">
              <i class="bi bi-trophy"></i> Leaderboard
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link" href="submit_formula.php">
              <i class="bi bi-plus-circle"></i> Submit Formula
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link" href="page3.php">Page 3</a>
          </li>
          <?php if ($is_moderator): ?>
          <li class="nav-item mb-2">
              <a class="nav-link" href="moderate_formulas.php">
                  <i class="bi bi-shield-check"></i> Moderate Formulas
              </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <h2>Welcome to the Dashboard</h2>
        <div class="row">
          <?php if (empty($formulas)): ?>
            <div class="col-12">
              <div class="alert alert-info">No formulas available yet.</div>
            </div>
          <?php else: ?>
            <?php foreach ($formulas as $formula): ?>
              <div class="col-md-6 col-lg-4 mb-4">
                <a href="formula.php?id=<?= $formula['id'] ?>" class="card-link">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($formula['title']) ?></h5>
                      <p class="card-text"><?= nl2br(htmlspecialchars($formula['formula_text'])) ?></p>
                      <p class="card-text"><small class="text-muted">Score: <?= isset($formula['score']) ? intval($formula['score']) : 'N/A' ?></small></p>
                    </div>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

<?php include '../includes/footer.php'; ?>