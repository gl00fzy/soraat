<?php
session_start();

// ตรวจสอบว่ามีตะกร้าหรือยัง ถ้ายังให้สร้าง array เปล่า
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$act = isset($_GET['act']) ? $_GET['act'] : 'view';
$p_id = isset($_GET['p_id']) ? $_GET['p_id'] : 0;

// 1. เพิ่มสินค้าลงตะกร้า
if ($act == 'add' && $p_id > 0) {
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++; // ถ้ามีแล้ว ให้เพิ่มจำนวน
    } else {
        $_SESSION['cart'][$p_id] = 1; // ถ้ายังไม่มี ให้เริ่มที่ 1
    }
    // กลับไปหน้าเดิม
    header("Location: cart.php"); 
}

// 2. ลบสินค้าออกจากตะกร้า
if ($act == 'remove' && $p_id > 0) {
    unset($_SESSION['cart'][$p_id]);
    header("Location: cart.php");
}

// 3. อัปเดตจำนวนสินค้า (กรณีแก้ตัวเลขใน input)
if ($act == 'update') {
    $amount_array = $_POST['amount'];
    foreach ($amount_array as $p_id => $amount) {
        $amount = intval($amount);
        if ($amount > 0) {
            $_SESSION['cart'][$p_id] = $amount;
        } else {
            unset($_SESSION['cart'][$p_id]); // ถ้าจำนวน <= 0 ให้ลบออกจากตะกร้า
        }
    }
    header("Location: cart.php");
    exit();
}
?>