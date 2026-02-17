<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

if (!isset($_GET['id'])) { header("Location: orders.php"); exit(); }

// ดึงข้อมูลออเดอร์ พร้อมข้อมูลลูกค้า
$stmt = $pdo->prepare("SELECT o.*, u.fullname, u.address as user_address, u.email, u.username 
                        FROM orders o JOIN users u ON o.user_id = u.id 
                        WHERE o.id = ?");
$stmt->execute([$_GET['id']]);
$order = $stmt->fetch();

if (!$order) { die("ไม่พบออเดอร์"); }

// ดึงรายการสินค้าในออเดอร์
$stmt_items = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->execute([$order['id']]);

// กำหนดสี status badge
$status_colors = [
    'pending' => 'admin-badge-pending',
    'paid' => 'admin-badge-paid', 
    'shipped' => 'admin-badge-shipped',
    'cancelled' => 'admin-badge-cancelled'
];
$status_class = $status_colors[$order['status']] ?? '';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์ #<?php echo $order['id']; ?> — MY SHOP Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="admin-body">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 admin-sidebar">
            <a class="sidebar-brand" href="dashboard.php">✦ MY SHOP</a>
            <span class="sidebar-subtitle">Admin Panel</span>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link"><i class="bi bi-box-seam"></i>จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link"><i class="bi bi-tags"></i>จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link active"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="admin-page-title mb-0"><i class="bi bi-receipt"></i>ออเดอร์ #<?php echo $order['id']; ?></h2>
                <a href="orders.php" class="btn btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>กลับหน้าจัดการออเดอร์</a>
            </div>

            <div class="row g-4">
                <!-- ข้อมูลลูกค้า -->
                <div class="col-md-6 admin-animate">
                    <div class="admin-card h-100">
                        <div class="card-header">
                            <h5><i class="bi bi-person me-2" style="color:var(--admin-primary);"></i>ข้อมูลลูกค้า</h5>
                        </div>
                        <div class="card-body">
                            <table class="table admin-table table-borderless mb-0">
                                <tr><td style="width:35%;color:var(--admin-text-muted);">ชื่อผู้ใช้</td><td><?php echo htmlspecialchars($order['username']); ?></td></tr>
                                <tr><td style="color:var(--admin-text-muted);">ชื่อ-นามสกุล</td><td class="fw-medium"><?php echo htmlspecialchars($order['fullname']); ?></td></tr>
                                <tr><td style="color:var(--admin-text-muted);">อีเมล</td><td><?php echo htmlspecialchars($order['email']); ?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ข้อมูลออเดอร์ -->
                <div class="col-md-6 admin-animate admin-animate-delay-1">
                    <div class="admin-card h-100">
                        <div class="card-header">
                            <h5><i class="bi bi-info-circle me-2" style="color:var(--admin-accent);"></i>ข้อมูลออเดอร์</h5>
                        </div>
                        <div class="card-body">
                            <table class="table admin-table table-borderless mb-0">
                                <tr><td style="width:35%;color:var(--admin-text-muted);">วันที่สั่งซื้อ</td><td><?php echo $order['order_date']; ?></td></tr>
                                <tr><td style="color:var(--admin-text-muted);">ยอดรวม</td><td class="fw-bold" style="color:var(--admin-success); font-size:1.1rem;"><?php echo number_format($order['total_price'], 2); ?> ฿</td></tr>
                                <tr><td style="color:var(--admin-text-muted);">สถานะ</td><td><span class="admin-badge <?php echo $status_class; ?>"><?php echo ucfirst($order['status']); ?></span></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ที่อยู่จัดส่ง -->
                <div class="col-md-6 admin-animate admin-animate-delay-2">
                    <div class="admin-card h-100">
                        <div class="card-header">
                            <h5><i class="bi bi-truck me-2" style="color:var(--admin-warning);"></i>ที่อยู่จัดส่ง</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            // แสดง shipping_address ของออเดอร์ (ถ้ามี) หรือ address ของ user
                            $shipping = !empty($order['shipping_address']) ? $order['shipping_address'] : ($order['user_address'] ?? '—');
                            ?>
                            <p style="line-height:1.8; color:var(--admin-text-secondary);">
                                <i class="bi bi-geo-alt me-1" style="color:var(--admin-primary);"></i>
                                <?php echo nl2br(htmlspecialchars($shipping)); ?>
                            </p>
                            <?php if(!empty($order['shipping_address']) && $order['shipping_address'] != ($order['user_address'] ?? '')): ?>
                            <small style="color:var(--admin-text-muted);">
                                <i class="bi bi-info-circle me-1"></i>ที่อยู่จัดส่งเฉพาะออเดอร์นี้ (ไม่ใช่ที่อยู่หลักของลูกค้า)
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- สลิปการชำระเงิน -->
                <div class="col-md-6 admin-animate admin-animate-delay-2">
                    <div class="admin-card h-100">
                        <div class="card-header">
                            <h5><i class="bi bi-credit-card me-2" style="color:var(--admin-success);"></i>หลักฐานการชำระเงิน</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($order['payment_slip'])): ?>
                                <div class="text-center">
                                    <img src="../<?php echo htmlspecialchars($order['payment_slip']); ?>" 
                                         style="max-width:260px; max-height:350px; border-radius:12px; border:1px solid var(--admin-card-border);" alt="สลิปโอนเงิน">
                                    <?php if(!empty($order['payment_date'])): ?>
                                    <p class="mt-2" style="color:var(--admin-text-muted);">
                                        <i class="bi bi-clock me-1"></i>ชำระเมื่อ: <?php echo $order['payment_date']; ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="bi bi-receipt" style="font-size:2rem; color:var(--admin-text-muted);"></i>
                                    <p style="color:var(--admin-text-muted); margin-top:8px;">ยังไม่มีหลักฐานการชำระเงิน</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- รายการสินค้าในออเดอร์ -->
                <div class="col-12 admin-animate admin-animate-delay-3">
                    <div class="admin-card">
                        <div class="card-header">
                            <h5><i class="bi bi-bag me-2" style="color:var(--admin-primary);"></i>รายการสินค้า</h5>
                        </div>
                        <div class="card-body">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>รูป</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>ราคาต่อชิ้น</th>
                                        <th>จำนวน</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($item = $stmt_items->fetch()): ?>
                                    <tr>
                                        <td>
                                            <?php if($item['image']): ?>
                                            <img src="../<?php echo $item['image']; ?>" width="44" style="border-radius:8px; border:1px solid var(--admin-card-border);">
                                            <?php else: ?>
                                            <span style="color:var(--admin-text-muted);">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-medium"><?php echo htmlspecialchars($item['name'] ?? 'สินค้าถูกลบ'); ?></td>
                                        <td><?php echo number_format($item['price'], 2); ?> ฿</td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td class="fw-bold" style="color:var(--admin-success);"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> ฿</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold" style="font-size:1.05rem;">ยอดรวมทั้งหมด:</td>
                                        <td class="fw-bold" style="color:var(--admin-success); font-size:1.15rem;"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>