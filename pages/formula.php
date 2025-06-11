<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$formula_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Fetch formula
$stmt = $pdo->prepare("SELECT * FROM formulas WHERE id = :id AND status = 'approved'");
$stmt->execute([':id' => $formula_id]);
$formula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formula) {
    echo "<div class='alert alert-danger'>Formula not found or not approved.</div>";
    exit;
}

// Check if user already solved this formula
$attempt_stmt = $pdo->prepare("SELECT is_correct FROM formula_attempts WHERE user_id = :user_id AND formula_id = :formula_id");
$attempt_stmt->execute([':user_id' => $user_id, ':formula_id' => $formula_id]);
$attempt = $attempt_stmt->fetch(PDO::FETCH_ASSOC);

$message = '';
$show_solution = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$attempt) {
    $user_answer = trim($_POST['answer'] ?? '');

    // Compare answer (case-insensitive, trimmed)
    $correct = (strtolower(trim($user_answer)) === strtolower(trim($formula['solution_text'])));
    $show_solution = true;

    // Save attempt
    $stmt = $pdo->prepare("INSERT INTO formula_attempts (user_id, formula_id, is_correct) VALUES (:user_id, :formula_id, :is_correct)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':formula_id' => $formula_id,
        ':is_correct' => $correct ? 1 : 0
    ]);

    if ($correct) {
        // Award score
        $score = intval($formula['score']);
        $pdo->prepare("UPDATE users SET score = score + :score WHERE id = :user_id")
            ->execute([':score' => $score, ':user_id' => $user_id]);
        $message = "<div class='alert alert-success'>Correct! You earned $score points.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Incorrect. Try again next time!</div>";
    }
} elseif ($attempt && $attempt['is_correct']) {
    $message = "<div class='alert alert-success'>You have already solved this formula and earned the points.</div>";
    $show_solution = true;
} elseif ($attempt && !$attempt['is_correct']) {
    $message = "<div class='alert alert-warning'>You have already attempted this formula.</div>";
    $show_solution = true;
}

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex" id="dashboard-flex">
        <?php include '../includes/sidebar.php'; ?>
        <main id="main-content" class="container mt-5">
            <h2><?= htmlspecialchars($formula['title']) ?></h2>
            <div class="card mb-3">
                <div class="card-body">
                    <p><strong>Formula:</strong></p>
                    <pre><?= htmlspecialchars($formula['formula_text']) ?></pre>
                    <p><strong>Score:</strong> <?= intval($formula['score']) ?></p>
                    <?php if ($message) echo $message; ?>

                    <?php if (!$attempt): ?>
                        <form method="post" class="mb-3">
                            <div class="mb-3">
                                <label for="answer" class="form-label">Your Answer:</label>
                                <input type="text" name="answer" id="answer" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Answer</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($show_solution): ?>
                        <div class="alert alert-info mt-3">
                            <strong>Solution:</strong> <?= nl2br(htmlspecialchars($formula['solution_text'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>