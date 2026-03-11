<?php
$host = '45.136.255.138';
$db   = 'shop_project';
$user = 'root';
$pass = 'Golf@2004';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected successfully to remote database.\n";
    
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
    
    foreach ($products as $p) {
        if (empty($p['image'])) {
            echo "NO IMAGE: ID " . $p['id'] . " - " . $p['name'] . "\n";
        } else {
            echo "HAS IMAGE: ID " . $p['id'] . " - " . $p['name'] . "\n";
        }
    }
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
