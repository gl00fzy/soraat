<?php
/**
 * สคริปต์แก้ไข Permission โฟลเดอร์ uploads
 * เปิดลิงก์นี้ในเบราว์เซอร์ 1 ครั้ง แล้วลบทิ้งได้เลย
 */
require_once '../db.php';

$target_dir = "../uploads/";

// สร้างโฟลเดอร์ถ้ายังไม่มี
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
    echo "สร้างโฟลเดอร์ uploads/ สำเร็จ<br>";
}

// แก้ไข permission
if (chmod($target_dir, 0777)) {
    echo "✅ แก้ไข permission เป็น 777 สำเร็จ!<br>";
} else {
    echo "❌ แก้ไข permission ไม่สำเร็จ — ลองใช้ FTP หรือ File Manager ของ Hosting เปลี่ยนเป็น 777<br>";
}

// ตรวจสอบผลลัพธ์
echo "<br>ตรวจสอบ:<br>";
echo "Path: " . realpath($target_dir) . "<br>";
echo "Writable: " . (is_writable($target_dir) ? '✅ YES' : '❌ NO') . "<br>";
echo "<br><strong>ถ้าแสดง Writable: YES แล้ว ให้กลับไปเพิ่ม/แก้ไขสินค้าพร้อมรูปภาพใหม่ได้เลย</strong>";
echo "<br><small style='color:gray;'>เมื่อใช้งานเสร็จ ให้ลบไฟล์ fix_permission.php ออก</small>";
?>
