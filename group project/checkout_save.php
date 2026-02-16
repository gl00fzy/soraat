<?php
require_once 'db.php';

// เช็คว่าล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// เช็คว่ามีของในตะกร้าไหม
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

try {
    $pdo->beginTransaction(); // เริ่ม Transaction เพื่อความปลอดภัยของข้อมูล

    // 1. คำนวณราคารวมอีกครั้ง (เพื่อความชัวร์ ห้ามเชื่อค่าจากหน้าเว็บ)
    $product_ids = array_keys($_SESSION['cart']);
    $ids = implode(',', $product_ids);
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_price = 0;
    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $total_price += $product['price'] * $qty;
    }

    // 2. บันทึกลงตาราง orders
    $sql_order = "INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, 'pending', NOW())";
    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([$_SESSION['user_id'], $total_price]);
    
    $order_id = $pdo->lastInsertId(); // เอา ID ใบสั่งซื้อล่าสุดมาใช้ต่อ

    // 3. บันทึกลงตาราง order_items (รายละเอียดสินค้าในบิล)
    $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_item = $pdo->prepare($sql_item);

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $price = $product['price']; // ราคา ณ วันที่ซื้อ
        $stmt_item->execute([$order_id, $product['id'], $qty, $price]);
    }

    $pdo->commit(); // ยืนยันการบันทึกข้อมูลทั้งหมด

    // 4. ล้างตะกร้าและแจ้งเตือน
    unset($_SESSION['cart']);
    echo "<script>alert('สั่งซื้อสำเร็จ! รหัสออเดอร์ของคุณคือ #$order_id'); window.location='profile.php';</script>";

} catch (Exception $e) {
    $pdo->rollBack(); // ถ้ามีอะไรผิดพลาด ให้ยกเลิก SQL ทั้งหมด
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>