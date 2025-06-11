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

<div class="container-fluid">
  <div class="d-flex" id="dashboard-flex">
    <?php include '../includes/sidebar.php'; ?>
    <main id="main-content" class="flex-grow-1 px-md-4 py-4">
      <div class="container" style="max-width: 1000px;">
        <h2 class="mb-4">Welcome to the Dashboard</h2>
        <div id="formula-row" class="row g-4">
          <!-- Cards here -->
        </div>
        <div id="loading" class="text-center my-3" style="display:none;">
          <div class="spinner-border text-primary"></div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
// Add this new code for formula loading
let offset = 0;
const limit = 9;
let loading = false;
let endReached = false;

function createCard(formula) {
  if (!formula) {
    return `
      <div class="col-md-4 mb-4">
        <div class="card formula-card h-100">
          <div class="card-body" style="height: 200px;"> <!-- Added fixed height -->
            <h5 class="card-title">No Data</h5>
            <p class="card-text">No formula available.</p>
          </div>
        </div>
      </div>
    `;
  }

  return `
  <div class="col-md-4 mb-4">
    <a href="formula.php?id=${formula.id}" class="card-link">
      <div class="card formula-card h-100">
        <div class="card-body">
          <h5 class="card-title">${formula.title}</h5>
          <p class="card-text">${formula.formula_text}</p>
          <p class="card-text"><small class="text-muted">Score: ${formula.score}</small></p>
        </div>
      </div>
    </a>
  </div>
`;
}

function loadFormulas() {
  if (loading) return;
  
  loading = true;
  document.getElementById('loading').style.display = 'block';

  fetch(`fetch_formulas.php?offset=${offset}&limit=${limit}`)
    .then(res => res.json())
    .then(data => {
      let row = document.getElementById('formula-row');
      
      // Add real formulas if any
      data.formulas.forEach(formula => {
        row.insertAdjacentHTML('beforeend', createCard(formula));
      });
      
      // Calculate how many placeholders needed to fill viewport plus extra for scrolling
      const cardHeight = 200; // Approximate height of a card in pixels
      const viewportHeight = window.innerHeight;
      const rowHeight = cardHeight + 32; // card height + margin/padding
      const rowsNeeded = Math.ceil((viewportHeight * 1.5) / rowHeight); // 150% of viewport
      const cardsNeeded = rowsNeeded * 3; // 3 cards per row
      
      // Add enough placeholders to ensure scrolling is possible
      for (let i = 0; i < cardsNeeded; i++) {
        row.insertAdjacentHTML('beforeend', createCard(null));
      }
      
      offset += data.formulas.length;

      // Make sure we have enough height for scrolling
      document.getElementById('main-content').style.minHeight = 
        (viewportHeight * 1.5) + 'px';
    })
    .catch(err => console.error('Error loading formulas:', err))
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
</script>

<?php include '../includes/footer.php'; ?>