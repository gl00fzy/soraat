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

// เช็คว่ามาจากฟอร์ม checkout หรือเปล่า
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
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

    // 2. รับข้อมูลที่อยู่จัดส่ง
    $shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
    
    // 3. จัดการสลิปโอนเงิน
    $payment_slip_path = '';
    if (isset($_FILES['payment_slip']['name']) && $_FILES['payment_slip']['name'] != '') {
        $ext = pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION);
        $new_name = "slip_" . uniqid() . "." . $ext;
        $target_dir = "uploads/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $upload_path = $target_dir . $new_name;
        if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $upload_path)) {
            $payment_slip_path = $upload_path;
        }
    }

    // 4. บันทึกลงตาราง orders (พยายามใส่ shipping_address, payment_slip, payment_date ถ้า column มี)
    $status = !empty($payment_slip_path) ? 'paid' : 'pending';
    
    try {
        // ลองใส่ field ใหม่
        $sql_order = "INSERT INTO orders (user_id, total_price, status, shipping_address, payment_slip, payment_date, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt_order = $pdo->prepare($sql_order);
        $payment_date = !empty($payment_slip_path) ? date('Y-m-d H:i:s') : null;
        $stmt_order->execute([$_SESSION['user_id'], $total_price, $status, $shipping_address, $payment_slip_path, $payment_date]);
    } catch (PDOException $e) {
        // ถ้า column ใหม่ยังไม่มี ให้ใช้ SQL เดิม
        $sql_order = "INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, ?, NOW())";
        $stmt_order = $pdo->prepare($sql_order);
        $stmt_order->execute([$_SESSION['user_id'], $total_price, $status]);
    }
    
    $order_id = $pdo->lastInsertId(); // เอา ID ใบสั่งซื้อล่าสุดมาใช้ต่อ

    // 5. บันทึกลงตาราง order_items (รายละเอียดสินค้าในบิล)
    $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_item = $pdo->prepare($sql_item);

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $price = $product['price']; // ราคา ณ วันที่ซื้อ
        $stmt_item->execute([$order_id, $product['id'], $qty, $price]);
    }

    // 6. อัปเดตเบอร์โทรลูกค้า (ถ้ากรอกมา)
    if (!empty($_POST['phone'])) {
        try {
            $pdo->prepare("UPDATE users SET phone = ? WHERE id = ?")->execute([$_POST['phone'], $_SESSION['user_id']]);
        } catch (PDOException $e) {
            // phone column ยังไม่มี — ไม่เป็นไร
        }
    }

    $pdo->commit(); // ยืนยันการบันทึกข้อมูลทั้งหมด

    // 7. ล้างตะกร้าและแจ้งเตือน
    unset($_SESSION['cart']);
    echo "<script>alert('สั่งซื้อสำเร็จ! รหัสออเดอร์ของคุณคือ #$order_id'); window.location='profile.php';</script>";

} catch (Exception $e) {
    $pdo->rollBack(); // ถ้ามีอะไรผิดพลาด ให้ยกเลิก SQL ทั้งหมด
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>