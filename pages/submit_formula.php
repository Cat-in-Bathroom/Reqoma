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

// Fetch all available tags
$tags_stmt = $pdo->query("SELECT id, name FROM tags ORDER BY name");
$all_tags = $tags_stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $formula_text = trim($_POST['formula_text']);
    $solution_text = trim($_POST['solution_text']);
    $difficulty = floatval($_POST['difficulty']);
    $user_id = $_SESSION['user_id'];
    $calculator_used = isset($_POST['calculator_used']) ? 1 : 0;

    if (empty($title) || empty($formula_text) || empty($solution_text)) {
        $error = "Please fill in all fields.";
    } else {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO formulas (title, formula_text, solution_text, difficulty, user_id, calculator_used) VALUES (:title, :formula_text, :solution_text, :difficulty, :user_id, :calculator_used)");
            $stmt->execute([
                ':title' => $title,
                ':formula_text' => $formula_text,
                ':solution_text' => $solution_text,
                ':difficulty' => $difficulty,
                ':user_id' => $user_id,
                ':calculator_used' => $calculator_used
            ]);
            $formula_id = $pdo->lastInsertId();

            // Insert tags
            if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
                $tag_stmt = $pdo->prepare("INSERT INTO formula_tags (formula_id, tag_id) VALUES (:formula_id, :tag_id)");
                foreach ($_POST['tags'] as $tag_id) {
                    $tag_stmt->execute([
                        ':formula_id' => $formula_id,
                        ':tag_id' => intval($tag_id)
                    ]);
                }
            }
            $pdo->commit();
            $message = "Formula submitted successfully! Your submission will be checked by moderation before it becomes public.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error submitting formula: " . $e->getMessage();
        }
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
        <div class="mb-3">
            <label class="form-label">Tags</label>
            <select class="form-select" id="tags" name="tags[]" multiple="multiple" style="width:100%">
                <?php foreach ($all_tags as $tag): ?>
                    <option value="<?= $tag['id'] ?>"><?= htmlspecialchars($tag['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="calculator_used" name="calculator_used" value="1">
            <label class="form-check-label" for="calculator_used">Calculator used</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit Formula</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

$(document).ready(function() {
    $('#tags').select2({
        placeholder: "Select tags",
        allowClear: true
    });
});
</script>

<?php include '../includes/footer.php'; ?>