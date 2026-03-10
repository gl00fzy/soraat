<?php
require_once 'includes/db.php';

header('Content-Type: application/json; charset=utf-8');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (mb_strlen($query, 'UTF-8') < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.price, p.image, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.name LIKE ? OR p.description LIKE ?
    ORDER BY p.name ASC
    LIMIT 6
");
$stmt->execute(["%$query%", "%$query%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
