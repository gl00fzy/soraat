<?php
require_once 'db.php';
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns in users: " . implode(", ", $columns) . "\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
