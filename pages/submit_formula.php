<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $formula_text = trim($_POST['formula_text']);
    $solution_text = trim($_POST['solution_text']);
    $difficulty = floatval($_POST['difficulty']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($formula_text) || empty($solution_text) || $difficulty < 1 || $difficulty > 5) {
        $error = "Please fill in all fields and set difficulty between 1 and 5.";
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
    <form method="post">
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
            <label class="form-label">Difficulty (1.0 = easy, 5.0 = hard)</label>
            <input type="number" name="difficulty" class="form-control" min="1" max="5" step="0.1" value="1.0" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Formula</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>