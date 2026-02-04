<?php
session_start();

unset($_SESSION['aid']);
unset($_SESSION['aname']);

echo "<script>";
echo "window.location='index.php';"; // ส่งไปหน้าถัดไป (ถ้ามี)
echo "</script>";
?>