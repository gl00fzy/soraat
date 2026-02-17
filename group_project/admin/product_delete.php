<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ตรวจสอบว่าส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. ดึงชื่อไฟล์รูปภาพออกมาก่อน เพื่อจะลบทิ้ง
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    // ถ้ามีรูป และไฟล์รูปนั้นมีอยู่จริงใน Server ให้ลบออก
    if ($product['image'] && file_exists("../" . $product['image'])) {
        unlink("../" . $product['image']);
    }

    // 2. ลบข้อมูลจากฐานข้อมูล (ต้องระวังเรื่อง Foreign KeyConstraint ถ้าสินค้านั้นเคยถูกสั่งซื้อไปแล้ว อาจจะลบไม่ได้ในบางดีไซน์ DB)
    // วิธีแก้ปัญหา Foreign Key: ส่วนใหญ่เราจะไม่ลบสินค้าจริงๆ (Hard Delete) แต่จะใช้การ "ซ่อน" (Soft Delete) แทน
    // แต่ในโปรเจกต์เรียนระดับนี้ มักอนุญาตให้ลบได้เลย หรือถ้าติด Error ให้ลบ Order ที่เกี่ยวข้องก่อน
    
    try {
        $del_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $del_stmt->execute([$id]);
        
        // ส่งกลับหน้าเดิมพร้อมข้อความแจ้งเตือน (ผ่าน URL Parameter หรือ Session ก็ได้ แต่วิธีนี้ง่ายสุด)
        echo "<script>alert('ลบสินค้าเรียบร้อย'); window.location='products.php';</script>";
        
    } catch (PDOException $e) {
        // กรณีลบไม่ได้ (เช่น ติด Foreign Key กับตาราง Order)
        echo "<script>alert('ไม่สามารถลบสินค้านี้ได้ เนื่องจากมีประวัติการสั่งซื้อแล้ว'); window.location='products.php';</script>";
    }

} else {
    // ถ้าไม่มี ID ส่งมา ให้กลับหน้าหลักเลย
    header("Location: products.php");
}
?>