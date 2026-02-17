<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// 1. ดึงข้อมูลสรุป (Stats)
// นับจำนวนออเดอร์ทั้งหมด
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$total_orders = $stmt->fetchColumn();

// นับยอดขายรวม (เฉพาะที่จ่ายเงินแล้ว หรือจะนับทั้งหมดก็ได้)
$stmt = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status != 'cancelled'");
$total_sales = $stmt->fetchColumn();

// นับจำนวนสินค้า
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$total_products = $stmt->fetchColumn();

// นับจำนวนลูกค้า
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
$total_users = $stmt->fetchColumn();

// 2. ดึงออเดอร์ล่าสุด 5 รายการ
$stmt = $pdo->query("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
            <h4>Admin Panel</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white active">Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link text-white">จัดการสินค้า</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link text-white">จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link text-danger mt-5">ออกจากระบบ</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <h2 class="mb-4">ภาพรวมร้านค้า</h2>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">ยอดขายรวม</h5>
                            <h2 class="card-text"><?php echo number_format($total_sales, 2); ?> ฿</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title">คำสั่งซื้อทั้งหมด</h5>
                            <h2 class="card-text"><?php echo number_format($total_orders); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <h5 class="card-title">สินค้าในคลัง</h5>
                            <h2 class="card-text"><?php echo number_format($total_products); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <h5 class="card-title">ลูกค้าสมาชิก</h5>
                            <h2 class="card-text"><?php echo number_format($total_users); ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">คำสั่งซื้อล่าสุด (5 รายการ)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
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
                                    'pending' => 'bg-secondary',
                                    'paid' => 'bg-info',
                                    'shipped' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['order_date'])); ?></td>
                                <td><?php echo number_format($row['total_price'], 2); ?></td>
                                <td><span class="badge <?php echo $badge; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td><a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">ดู</a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="text-end mt-3">
                        <a href="orders.php" class="btn btn-link">ดูรายการทั้งหมด &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>