<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// โค้ดอัปเดตสถานะ (เมื่อกดปุ่มบันทึกจาก Modal หรือ Form)
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    echo "<script>alert('อัปเดตสถานะเรียบร้อย'); window.location='orders.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - จัดการออเดอร์ — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    <style>
        /* DataTable dark theme overrides */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            background: rgba(255,255,255,0.06) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: var(--admin-text) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            color: var(--admin-text-muted) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--admin-text-secondary) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            background: transparent !important;
            border-radius: 6px !important;
            margin: 0 2px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgba(102,126,234,0.2) !important;
            color: #fff !important;
            border-color: var(--admin-primary) !important;
        }
    </style>
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
            <h2 class="admin-page-title"><i class="bi bi-receipt"></i>รายการคำสั่งซื้อ</h2>
            
            <div class="admin-card admin-animate">
                <div class="card-body">
                    <table id="orderTable" class="table admin-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>ลูกค้า</th>
                                <th>ยอดรวม</th>
                                <th>วันที่สั่ง</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT o.*, u.fullname FROM orders o 
                                    JOIN users u ON o.user_id = u.id 
                                    ORDER BY o.order_date DESC";
                            $stmt = $pdo->query($sql);
                            
                            while ($row = $stmt->fetch()) {
                                $badge_class = match($row['status']) {
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
                                <td class="fw-bold" style="color:var(--admin-success);"><?php echo number_format($row['total_price'], 2); ?> ฿</td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                <td><span class="admin-badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td>
                                    <a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-info"><i class="bi bi-eye"></i></a>
                                    
                                    <button type="button" class="btn btn-sm btn-admin-warning" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $row['id']; ?>">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>

                                    <div class="modal fade" id="statusModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form method="post" class="modal-content admin-modal">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-arrow-repeat me-2"></i>อัปเดตสถานะ Order #<?php echo $row['id']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <label class="form-label" style="color:var(--admin-text-secondary);">เลือกสถานะใหม่</label>
                                                    <select name="status" class="form-select" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--admin-text);border-radius:8px;">
                                                        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending (รอชำระ)</option>
                                                        <option value="paid" <?php if($row['status']=='paid') echo 'selected'; ?>>Paid (ชำระแล้ว)</option>
                                                        <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>Shipped (จัดส่งแล้ว)</option>
                                                        <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled (ยกเลิก)</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="update_status" class="btn btn-admin-primary">
                                                        <i class="bi bi-check-lg me-1"></i>บันทึก
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#orderTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json" } });
    });
</script>
</body>
</html>