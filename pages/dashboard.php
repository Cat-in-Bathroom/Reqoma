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
      transition: margin-left 0.3s cubic-bezier(.4,2,.6,1), opacity 0.3s;
      opacity: 1;
    }
    .sidebar-hidden {
      margin-left: -260px; /* Adjust to sidebar width */
      opacity: 0;
      pointer-events: none;
    }
    .sidebar-visible {
      margin-left: 0;
      opacity: 1;
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
      <!-- Sidebar Toggle Button (visible on all screens now) -->
      <button id="sidebarToggle" class="btn btn-secondary btn-sm position-absolute top-0 start-0 m-3" type="button" style="z-index:1051;">
        <i class="bi bi-list"></i>
      </button>
      <!-- Sidebar -->
      <nav id="sidebar" class="col-md-3 col-lg-2 sidebar p-3 sidebar-visible">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="text-white mb-0">Dashboard</h4>
          <button id="sidebarToggle" class="btn btn-secondary btn-sm" type="button" title="Toggle Sidebar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
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
        <div id="formula-row" class="row justify-content-center">
          <!-- Cards will be loaded here -->
        </div>
        <div id="loading" class="text-center my-3" style="display:none;">
          <div class="spinner-border text-primary"></div>
        </div>
      </main>
      <script>
        let offset = 0;
        const limit = 9; // Load 9 at a time (3 rows)
        let loading = false;
        let endReached = false;

        function createCard(formula) {
          if (!formula) {
            return `
              <div class="col-md-4 mb-4">
                <div class="card border-secondary text-center">
                  <div class="card-body">
                    <h5 class="card-title text-muted">No Data</h5>
                    <p class="card-text text-muted">No formula available.</p>
                  </div>
                </div>
              </div>
            `;
          }
          return `
            <div class="col-md-4 mb-4">
              <a href="formula.php?id=${formula.id}" class="card-link w-100">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">${formula.title}</h5>
                    <p class="card-text">${formula.formula_text}</p>
                    <p class="card-text"><small class="text-muted">Score: ${formula.score ?? 'N/A'}</small></p>
                  </div>
                </div>
              </a>
            </div>
          `;
        }

        function loadFormulas() {
          if (loading || endReached) return;
          loading = true;
          document.getElementById('loading').style.display = 'block';
          fetch(`fetch_formulas.php?offset=${offset}&limit=${limit}`)
            .then(res => res.json())
            .then(data => {
              let row = document.getElementById('formula-row');
              if (data.formulas.length === 0 && offset === 0) {
                // No data at all, show 3 placeholders
                for (let i = 0; i < 3; i++) row.innerHTML += createCard(null);
                endReached = true;
              } else if (data.formulas.length === 0) {
                endReached = true;
              } else {
                // Always fill up to a multiple of 3 for nice rows
                let cards = data.formulas.map(createCard);
                while (cards.length % 3 !== 0) cards.push(createCard(null));
                row.innerHTML += cards.join('');
                offset += data.formulas.length;
                if (data.formulas.length < limit) endReached = true;
              }
            })
            .finally(() => {
              loading = false;
              document.getElementById('loading').style.display = 'none';
            });
        }

        // Initial load
        loadFormulas();

        // Infinite scroll
        window.addEventListener('scroll', () => {
          if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 200)) {
            loadFormulas();
          }
        });

        // Sidebar toggle for all screen sizes
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarShow = document.getElementById('sidebarShow');

        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('sidebar-hidden');
          sidebar.classList.toggle('sidebar-visible');
          sidebarShow.style.display = sidebar.classList.contains('sidebar-hidden') ? 'block' : 'none';
        });

        sidebarShow.addEventListener('click', function() {
          sidebar.classList.remove('sidebar-hidden');
          sidebar.classList.add('sidebar-visible');
          sidebarShow.style.display = 'none';
        });
      </script>
    </div>
  </div>

<?php include '../includes/footer.php'; ?>