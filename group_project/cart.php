<?php
require_once 'db.php';

// ดึง ID สินค้าทั้งหมดในตะกร้าออกมาเพื่อไป Query ข้อมูล
$product_ids = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : array();

if (empty($product_ids)) {
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="particles-bg">
    <?php for($j = 0; $j < 15; $j++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $j * 0.8; ?>s;"></div>
    <?php endfor; ?>
</div>
<div class="content-wrapper">
    <div class="empty-state" style="padding-top:120px;">
        <i class="bi bi-bag-x"></i>
        <h3>ตะกร้าสินค้าว่างเปล่า</h3>
        <p style="color:var(--text-muted);">คุณยังไม่ได้เพิ่มสินค้าในตะกร้า</p>
        <a href="index.php" class="btn btn-gradient mt-3"><i class="bi bi-arrow-left me-2"></i>กลับไปเลือกสินค้า</a>
    </div>
</div>
</body>
</html>
<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 15; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.8; ?>s;"></div>
    <?php endfor; ?>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top premium-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">✦ MY SHOP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door me-1"></i>หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link active" href="cart.php"><i class="bi bi-bag me-1"></i>ตะกร้าสินค้า</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person me-1"></i>โปรไฟล์</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบ</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container" style="padding-top:100px;">
        <div class="section-header" style="margin-bottom:30px;">
            <h2><i class="bi bi-bag me-2" style="color:var(--primary)"></i>ตะกร้าสินค้าของคุณ</h2>
            <div class="accent-line"></div>
        </div>

        <form action="cart_action.php?act=update" method="post">
            <div class="glass-card" style="border-radius:var(--radius-lg);">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-glass mb-0">
                            <thead>
                                <tr>
                                    <th>สินค้า</th>
                                    <th>ราคาต่อชิ้น</th>
                                    <th style="width:130px;">จำนวน</th>
                                    <th>รวม</th>
                                    <th style="width:60px;">ลบ</th>
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
                                    <td class="fw-medium"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo number_format($row['price'], 2); ?> ฿</td>
                                    <td>
                                        <input type="number" name="amount[<?php echo $p_id; ?>]" value="<?php echo $qty; ?>" min="1" class="form-control qty-input">
                                    </td>
                                    <td class="gradient-text fw-bold"><?php echo number_format($subtotal, 2); ?> ฿</td>
                                    <td>
                                        <a href="cart_action.php?act=remove&p_id=<?php echo $p_id; ?>" class="btn btn-sm btn-danger-glass" title="ลบ">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold" style="color:var(--text-secondary);">ยอดรวมทั้งหมด</td>
                                    <td colspan="2" class="fw-bold gradient-text" style="font-size:1.3rem;"><?php echo number_format($total_price, 2); ?> ฿</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <a href="index.php" class="btn btn-outline-glass">
                    <i class="bi bi-arrow-left me-2"></i>เลือกซื้อสินค้าต่อ
                </a>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning-glass">
                        <i class="bi bi-arrow-clockwise me-1"></i>คำนวณราคาใหม่
                    </button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="checkout.php" class="btn btn-gradient px-4">
                            <i class="bi bi-credit-card me-1"></i>ดำเนินการชำระเงิน
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-gradient px-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบเพื่อสั่งซื้อ
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <footer class="premium-footer">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> MY SHOP — All rights reserved.</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>