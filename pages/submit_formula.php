<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

// Fetch user's base difficulty
$stmt = $pdo->prepare("SELECT difficulty FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user_difficulty = floatval($stmt->fetchColumn());

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $formula_text = trim($_POST['formula_text']);
    $solution_text = trim($_POST['solution_text']);
    $difficulty = floatval($_POST['difficulty']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($formula_text) || empty($solution_text)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO formulas (title, formula_text, solution_text, difficulty, user_id) VALUES (:title, :formula_text, :solution_text, :difficulty, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':formula_text' => $formula_text,
            ':solution_text' => $solution_text,
            ':difficulty' => $difficulty,
            ':user_id' => $user_id
        ]);
        $message = "Formula submitted successfully!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1>Submit a Math Formula</h1>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" id="formulaForm">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Formula</label>
            <textarea name="formula_text" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Solution / Answers</label>
            <textarea name="solution_text" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Difficulty</label>
            <div>
                <span>Your base difficulty: <strong id="baseDiff"><?= htmlspecialchars($user_difficulty) ?></strong></span>
            </div>
            <input type="range" min="0.5" max="2" step="0.01" value="1" id="diffMultiplier" class="form-range" style="width: 300px;">
            <div>
                <small>Multiplier: <span id="multiplierValue">1.00</span> &mdash; Resulting difficulty: <span id="resultDiff"><?= htmlspecialchars($user_difficulty) ?></span></small>
            </div>
            <input type="hidden" name="difficulty" id="difficultyInput" value="<?= htmlspecialchars($user_difficulty) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit Formula</button>
    </form>
</div>

<script>
const baseDiff = parseFloat(document.getElementById('baseDiff').textContent);
const slider = document.getElementById('diffMultiplier');
const multiplierValue = document.getElementById('multiplierValue');
const resultDiff = document.getElementById('resultDiff');
const difficultyInput = document.getElementById('difficultyInput');

slider.addEventListener('input', function() {
    const multiplier = parseFloat(slider.value);
    const calculated = (baseDiff * multiplier).toFixed(2);
    multiplierValue.textContent = multiplier.toFixed(2);
    resultDiff.textContent = calculated;
    difficultyInput.value = calculated;
});
</script>

<?php include '../includes/footer.php'; ?>