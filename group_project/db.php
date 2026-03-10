<?php
// กำหนดค่าเริ่มต้น (สำหรับใช้งานจริงบน Server Debian)
$host = 'localhost';
$dbname = 'shop_project';
$username = 'root';
// รหัสผ่านของฝั่ง Server Debian คือ Golf@2004
$password = 'Golf@2004'; 

// โหลดไฟล์ตั้งค่าของเครื่อง Localhost (เพื่อเขียนทับค่าหากเป็นการรันบน Local)
$local_config = __DIR__ . '/config.local.php';
if (file_exists($local_config)) {
    require_once $local_config;
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start(); // เริ่ม session ทุกครั้งที่มีการเรียกใช้ไฟล์นี้
?>