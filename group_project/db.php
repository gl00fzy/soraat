<?php
$host = 'localhost';
$dbname = 'shop_project';
$username = 'root'; // หรือ username ของคุณ
$password = 'Golf@2004'; // หรือ password ของคุณ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start(); // เริ่ม session ทุกครั้งที่มีการเรียกใช้ไฟล์นี้
?>