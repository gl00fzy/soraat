<?php
$host = 'localhost';
$dbname = 'shop_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "SUCCESS_EMPTY";
} catch (PDOException $e) {
    echo "ERROR_EMPTY: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, "Golf@2004");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "SUCCESS_GOLF";
} catch (PDOException $e) {
    echo "ERROR_GOLF: " . $e->getMessage() . "\n";
}
?>
