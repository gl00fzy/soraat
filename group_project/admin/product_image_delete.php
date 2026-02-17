<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

if (!isset($_GET['id']) || !isset($_GET['product_id'])) {
    header("Location: products.php");
    exit();
}

$img_id = $_GET['id'];
$product_id = $_GET['product_id'];

// ดึงข้อมูลรูปภาพ
$stmt = $pdo->prepare("SELECT * FROM product_images WHERE id = ?");
$stmt->execute([$img_id]);
$img = $stmt->fetch();

if ($img) {
    // ลบไฟล์จาก server
    $file_path = "../" . $img['image_path'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // ลบจาก database
    $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$img_id]);
}

header("Location: product_edit.php?id=" . $product_id);
exit();
?>
