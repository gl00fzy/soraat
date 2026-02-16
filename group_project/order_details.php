<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// ดึงข้อมูลหัวบิล (ต้องเช็คด้วยว่าเป็นของ user คนนี้จริงๆ เพื่อความปลอดภัย)
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) { die("ไม่พบข้อมูลคำสั่งซื้อ"); }

// ดึงรายการสินค้าในบิล
$stmt_items = $pdo->prepare("
    SELECT oi.*, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt_items->execute([$order_id]);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h4 class="mb-0">คำสั่งซื้อ #<?php echo $order_id; ?></h4>
                <a href="profile.php" class="btn btn-secondary btn-sm">ย้อนกลับ</a>
            </div>
            <div class="card-body">
                <p><strong>วันที่สั่งซื้อ:</strong> <?php echo $order['order_date']; ?></p>
                <p><strong>สถานะ:</strong> <?php echo ucfirst($order['status']); ?></p>
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>สินค้า</th>
                            <th>ราคาต่อชิ้น</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $stmt_items->fetch()) { ?>
                        <tr>
                            <td>
                                <?php if($item['image']): ?>
                                <img src="<?php echo $item['image']; ?>" width="50" class="me-2">
                                <?php endif; ?>
                                <?php echo $item['name']; ?>
                            </td>
                            <td><?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">ยอดรวมสุทธิ</td>
                            <td class="fw-bold text-success"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>