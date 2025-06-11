<?php
require_once __DIR__ . '/../includes/config.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9;

// Get total count first
$total_stmt = $pdo->query("SELECT COUNT(*) FROM formulas WHERE status = 'approved'");
$total_formulas = $total_stmt->fetchColumn();

// Then get the formulas for this page
$stmt = $pdo->prepare("SELECT id, title, formula_text, score FROM formulas WHERE status = 'approved' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$formulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return both the formulas and the total count
header('Content-Type: application/json');
echo json_encode([
    'formulas' => $formulas,
    'total' => $total_formulas,
    'hasMore' => ($offset + count($formulas)) < $total_formulas
]);