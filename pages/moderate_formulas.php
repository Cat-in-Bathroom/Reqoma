<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Check if user is moderator or admin
$user_id = $_SESSION['user_id'] ?? null;
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$role = $stmt->fetchColumn();

if ($role !== 'moderator' && $role !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Approve or reject logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formula_id'], $_POST['action'])) {
    $formula_id = intval($_POST['formula_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("UPDATE formulas SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $action, ':id' => $formula_id]);
}

// Fetch pending formulas
$stmt = $pdo->query("SELECT f.*, u.username FROM formulas f JOIN users u ON f.user_id = u.id WHERE f.status = 'pending' ORDER BY f.created_at ASC");
$formulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="container mt-5">
    <h1>Moderate Formula Submissions</h1>
    <?php if (empty($formulas)): ?>
        <div class="alert alert-info">No pending submissions.</div>
    <?php else: ?>
        <?php foreach ($formulas as $formula): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($formula['title']) ?></h5>
                    <p><strong>Submitted by:</strong> <?= htmlspecialchars($formula['username']) ?></p>
                    <p><strong>Formula:</strong> <?= nl2br(htmlspecialchars($formula['formula_text'])) ?></p>
                    <p><strong>Solution:</strong> <?= nl2br(htmlspecialchars($formula['solution_text'])) ?></p>
                    <p><strong>Difficulty:</strong> <?= htmlspecialchars($formula['difficulty']) ?></p>
                    <p><strong>Calculator used:</strong> <?= $formula['calculator_used'] ? 'Yes' : 'No' ?></p>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="formula_id" value="<?= $formula['id'] ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>