<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ลบลูกค้า
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - จัดการลูกค้า — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    <style>
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
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link active"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <h2 class="admin-page-title"><i class="bi bi-people"></i>จัดการลูกค้า</h2>

            <div class="admin-card admin-animate">
                <div class="card-body">
                    <table id="userTable" class="table admin-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>สิทธิ์</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM users");
                            while ($row = $stmt->fetch()) {
                            ?>
                            <tr>
                                <td class="fw-medium"><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td style="color:var(--admin-text-secondary);"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <?php if($row['role'] == 'admin'): ?>
                                    <span class="admin-badge" style="background:rgba(240,147,251,0.15); color:#f093fb;">Admin</span>
                                    <?php else: ?>
                                    <span class="admin-badge admin-badge-paid">Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-warning"><i class="bi bi-pencil"></i></a>
                                    <?php if($row['role'] != 'admin'): // ห้ามลบ Admin ?>
                                    <a href="users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-danger" onclick="return confirm('ยืนยันการลบ? ข้อมูลการสั่งซื้อของลูกค้านี้อาจหายไป')"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
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
        $('#userTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json" } });
    });
</script>
</body>
</html>