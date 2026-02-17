<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// 1. ดึงข้อมูลสรุป (Stats)
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$total_orders = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status != 'cancelled'");
$total_sales = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$total_products = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
$total_users = $stmt->fetchColumn();

// 2. ดึงออเดอร์ล่าสุด 5 รายการ
$stmt = $pdo->query("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — MY SHOP</title>
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
                <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link"><i class="bi bi-box-seam"></i>จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link"><i class="bi bi-tags"></i>จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <h2 class="admin-page-title"><i class="bi bi-speedometer2"></i>ภาพรวมร้านค้า</h2>
            
            <!-- Stat Cards -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 admin-animate admin-animate-delay-1">
                    <div class="stat-card stat-sales">
                        <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
                        <div class="stat-value" data-count="<?php echo round($total_sales); ?>"><?php echo number_format($total_sales, 2); ?> ฿</div>
                        <div class="stat-label">ยอดขายรวม</div>
                    </div>
                </div>
                <div class="col-md-3 admin-animate admin-animate-delay-2">
                    <div class="stat-card stat-orders">
                        <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
                        <div class="stat-value"><?php echo number_format($total_orders); ?></div>
                        <div class="stat-label">คำสั่งซื้อทั้งหมด</div>
                    </div>
                </div>
                <div class="col-md-3 admin-animate admin-animate-delay-3">
                    <div class="stat-card stat-products">
                        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                        <div class="stat-value"><?php echo number_format($total_products); ?></div>
                        <div class="stat-label">สินค้าในคลัง</div>
                    </div>
                </div>
                <div class="col-md-3 admin-animate admin-animate-delay-4">
                    <div class="stat-card stat-users">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div class="stat-value"><?php echo number_format($total_users); ?></div>
                        <div class="stat-label">ลูกค้าสมาชิก</div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-card admin-animate" style="animation-delay:0.5s;">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history me-2" style="color:var(--admin-primary)"></i>คำสั่งซื้อล่าสุด (5 รายการ)</h5>
                    <a href="orders.php" class="btn-admin-info btn btn-sm">ดูทั้งหมด <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>ลูกค้า</th>
                                    <th>วันที่</th>
                                    <th>ยอดเงิน</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $stmt->fetch()) { 
                                    $badge = match($row['status']) {
                                        'pending' => 'admin-badge-pending',
                                        'paid' => 'admin-badge-paid',
                                        'shipped' => 'admin-badge-shipped',
                                        'cancelled' => 'admin-badge-cancelled',
                                        default => 'admin-badge-pending'
                                    };
                                ?>
                                <tr>
                                    <td class="fw-medium">#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['order_date'])); ?></td>
                                    <td class="fw-bold" style="color:var(--admin-success);"><?php echo number_format($row['total_price'], 2); ?> ฿</td>
                                    <td><span class="admin-badge <?php echo $badge; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                    <td><a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-info"><i class="bi bi-eye me-1"></i>ดู</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
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