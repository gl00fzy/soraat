<?php
require_once 'db.php';

// ดึง ID สินค้าทั้งหมดในตะกร้าออกมาเพื่อไป Query ข้อมูล
$product_ids = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : array();

if (empty($product_ids)) {
    echo "<div class='container mt-5 pt-5 text-center'><h3>ตะกร้าว่างเปล่า</h3><a href='index.php' class='btn btn-primary'>กลับไปเลือกสินค้า</a></div>";
    exit(); 
}

// แปลง array เป็น string เพื่อใช้ใน SQL เช่น (1, 2, 5)
$ids = implode(',', $product_ids);
$sql = "SELECT * FROM products WHERE id IN ($ids)";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตะกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 pt-5">
        <h2 class="mb-4">ตะกร้าสินค้าของคุณ</h2>
        <form action="cart_action.php?act=update" method="post">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>สินค้า</th>
                                <th>ราคาต่อชิ้น</th>
                                <th width="150">จำนวน</th>
                                <th>รวม</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_price = 0;
                            foreach ($products as $row) {
                                $p_id = $row['id'];
                                $qty = $_SESSION['cart'][$p_id];
                                $subtotal = $row['price'] * $qty;
                                $total_price += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="amount[<?php echo $p_id; ?>]" value="<?php echo $qty; ?>" min="1" class="form-control text-center">
                                </td>
                                <td><?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <a href="cart_action.php?act=remove&p_id=<?php echo $p_id; ?>" class="btn btn-sm btn-danger">x</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">ยอดรวมทั้งหมด</td>
                                <td colspan="2" class="fw-bold text-success"><?php echo number_format($total_price, 2); ?> ฿</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-outline-secondary">← เลือกซื้อสินค้าต่อ</a>
                <div>
                    <button type="submit" class="btn btn-warning">คำนวณราคาใหม่</button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="checkout_save.php" class="btn btn-success px-4">ยืนยันการสั่งซื้อ →</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary px-4">เข้าสู่ระบบเพื่อสั่งซื้อ</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</body>
</html>