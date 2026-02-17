<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }
$order_id = $_GET['id'];

// ดึงข้อมูลออเดอร์ + ข้อมูลลูกค้า (ที่อยู่)
$stmt = $pdo->prepare("SELECT o.*, u.fullname, u.address, u.email, u.username 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.id 
                       WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// ดึงรายการสินค้า
$stmt_items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดออเดอร์ #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between mb-4">
            <h3>คำสั่งซื้อ #<?php echo $order_id; ?></h3>
            <a href="orders.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-primary">ข้อมูลลูกค้า & ที่อยู่จัดส่ง</h5>
                <p><strong>ชื่อ:</strong> <?php echo $order['fullname']; ?> (<?php echo $order['username']; ?>)</p>
                <p><strong>อีเมล:</strong> <?php echo $order['email']; ?></p>
                <div class="alert alert-secondary">
                    <strong>ที่อยู่จัดส่ง:</strong><br>
                    <?php echo nl2br($order['address']); ?>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <h5 class="text-primary">สถานะคำสั่งซื้อ</h5>
                <h2><span class="badge bg-info"><?php echo ucfirst($order['status']); ?></span></h2>
                <p>วันที่สั่ง: <?php echo $order['order_date']; ?></p>
            </div>
        </div>

        <h5 class="mt-4">รายการสินค้า</h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>สินค้า</th>
                    <th class="text-end">ราคา</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-end">รวม</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $stmt_items->fetch()) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td class="text-end"><?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">ยอดรวมสุทธิ</td>
                    <td class="text-end fw-bold text-success"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>