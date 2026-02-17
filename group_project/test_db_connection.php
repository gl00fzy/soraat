<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

echo "Database connection successful!<br>";

$tables = ['users', 'products', 'orders', 'order_details', 'categories'];
foreach ($tables as $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        echo "Table '$table' exists.<br>";
    } catch (PDOException $e) {
        echo "Error checking table '$table': " . $e->getMessage() . "<br>";
    }
}
?>
