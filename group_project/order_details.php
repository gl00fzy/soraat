<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if (!isset($_GET['id'])) { header("Location: profile.php"); exit(); }

$order_id = intval($_GET['id']);
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

$status = $order['status'];
$badge_class = 'badge-pending';
if($status == 'paid') $badge_class = 'badge-paid';
if($status == 'shipped') $badge_class = 'badge-shipped';
if($status == 'cancelled') $badge_class = 'badge-cancelled';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?> — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 12; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.9; ?>s;"></div>
    <?php endfor; ?>
</div>

<div class="content-wrapper">
    <div class="container" style="padding-top:60px; max-width:800px;">
        <div class="glass-card fade-in-up" style="border-radius:var(--radius-lg);">
            <!-- Header -->
            <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--glass-border);">
                <div>
                    <h4 class="mb-1" style="font-weight:700;">
                        <i class="bi bi-receipt me-2" style="color:var(--primary);"></i>คำสั่งซื้อ #<?php echo $order_id; ?>
                    </h4>
                    <small style="color:var(--text-muted);">
                        <i class="bi bi-calendar3 me-1"></i><?php echo $order['order_date']; ?>
                    </small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge-glass <?php echo $badge_class; ?>" style="font-size:0.9rem; padding:8px 18px;">
                        <?php echo ucfirst($status); ?>
                    </span>
                    <a href="profile.php" class="btn btn-outline-glass btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>ย้อนกลับ
                    </a>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-glass mb-0">
                        <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th class="text-end">ราคาต่อชิ้น</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $stmt_items->fetch()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($item['image']): ?>
                                        <img src="<?php echo $item['image']; ?>" width="40" style="border-radius:6px; border:1px solid var(--glass-border);">
                                        <?php endif; ?>
                                        <span class="fw-medium"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td class="text-end"><?php echo number_format($item['price'], 2); ?> ฿</td>
                                <td class="text-center"><?php echo $item['quantity']; ?></td>
                                <td class="text-end gradient-text fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> ฿</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold" style="color:var(--text-secondary);">ยอดรวมสุทธิ</td>
                                <td class="text-end gradient-text" style="font-size:1.3rem; font-weight:800;"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="premium-footer">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> MY SHOP — All rights reserved.</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.fade-in-up').forEach(el => {
    setTimeout(() => el.classList.add('visible'), 100);
});
</script>
</body>
</html>