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

$status = $order['status'];
$badge_class = 'admin-badge-pending';
if($status == 'paid') $badge_class = 'admin-badge-paid';
if($status == 'shipped') $badge_class = 'admin-badge-shipped';
if($status == 'cancelled') $badge_class = 'admin-badge-cancelled';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์ #<?php echo $order_id; ?> — MY SHOP Admin</title>
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
            <div class="admin-card admin-animate">
                <!-- Header -->
                <div class="card-header">
                    <h5><i class="bi bi-receipt me-2" style="color:var(--admin-primary);"></i>คำสั่งซื้อ #<?php echo $order_id; ?></h5>
                    <a href="orders.php" class="btn btn-sm btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 style="color:var(--admin-primary); font-weight:600; margin-bottom:12px;">
                                <i class="bi bi-person me-1"></i>ข้อมูลลูกค้า & ที่อยู่จัดส่ง
                            </h6>
                            <p style="margin-bottom:6px;"><strong>ชื่อ:</strong> <?php echo $order['fullname']; ?> (<?php echo $order['username']; ?>)</p>
                            <p style="margin-bottom:12px;"><strong>อีเมล:</strong> <?php echo $order['email']; ?></p>
                            <div style="background:rgba(255,255,255,0.04); border:1px solid var(--admin-card-border); border-radius:10px; padding:14px;">
                                <strong style="color:var(--admin-text-secondary);"><i class="bi bi-geo-alt me-1"></i>ที่อยู่จัดส่ง:</strong><br>
                                <?php echo nl2br($order['address']); ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 style="color:var(--admin-primary); font-weight:600; margin-bottom:12px;">
                                <i class="bi bi-info-circle me-1"></i>สถานะคำสั่งซื้อ
                            </h6>
                            <div class="mb-2">
                                <span class="admin-badge <?php echo $badge_class; ?>" style="font-size:1rem; padding:8px 20px;">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </div>
                            <p style="color:var(--admin-text-muted);"><i class="bi bi-calendar3 me-1"></i>วันที่สั่ง: <?php echo $order['order_date']; ?></p>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3" style="font-weight:600; color:var(--admin-text);">
                        <i class="bi bi-list-ul me-1" style="color:var(--admin-primary);"></i>รายการสินค้า
                    </h6>
                    <div class="table-responsive">
                        <table class="table admin-table">
                            <thead>
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
                                    <td class="text-end"><?php echo number_format($item['price'], 2); ?> ฿</td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end fw-bold" style="color:var(--admin-success);"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> ฿</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">ยอดรวมสุทธิ</td>
                                    <td class="text-end fw-bold" style="color:var(--admin-success); font-size:1.2rem;"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>