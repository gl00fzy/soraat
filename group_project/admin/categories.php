<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// --- ส่วนจัดการ Logic (PHP) ---

// 1. จัดการการบันทึกข้อมูล (เพิ่ม หรือ แก้ไข)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $id = $_POST['id'];

    if (!empty($name)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
        }
        header("Location: categories.php");
        exit();
    }
}

// 2. จัดการการลบ (Delete)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $check->execute([$id]);
    
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('ไม่สามารถลบได้ เนื่องจากมีสินค้าอยู่ในหมวดหมู่นี้'); window.location='categories.php';</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: categories.php");
    }
    exit();
}

// 3. ดึงข้อมูลมาใส่ฟอร์ม กรณีจะแก้ไข (Edit Mode)
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - จัดการประเภทสินค้า — MY SHOP</title>
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
                <li class="nav-item"><a href="categories.php" class="nav-link active"><i class="bi bi-tags"></i>จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <h2 class="admin-page-title"><i class="bi bi-tags"></i>จัดการประเภทสินค้า</h2>

            <div class="row g-4">
                <!-- Add/Edit Form -->
                <div class="col-md-4 admin-animate admin-animate-delay-1">
                    <div class="admin-card">
                        <div class="card-header">
                            <h5>
                                <i class="bi bi-<?php echo $edit_data ? 'pencil' : 'plus-circle'; ?> me-2" style="color:var(--admin-primary);"></i>
                                <?php echo $edit_data ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้าใหม่'; ?>
                            </h5>
                        </div>
                        <div class="card-body admin-form">
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อหมวดหมู่</label>
                                    <input type="text" name="name" class="form-control" required 
                                           value="<?php echo $edit_data['name'] ?? ''; ?>" 
                                           placeholder="เช่น เสื้อผ้า, อุปกรณ์ไอที">
                                </div>
                                <button type="submit" class="btn btn-admin-success w-100">
                                    <i class="bi bi-<?php echo $edit_data ? 'check-lg' : 'plus-lg'; ?> me-1"></i>
                                    <?php echo $edit_data ? 'บันทึกการแก้ไข' : 'เพิ่มข้อมูล'; ?>
                                </button>
                                <?php if($edit_data): ?>
                                    <a href="categories.php" class="btn btn-admin-secondary w-100 mt-2">
                                        <i class="bi bi-x-lg me-1"></i>ยกเลิก
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Categories Table -->
                <div class="col-md-8 admin-animate admin-animate-delay-2">
                    <div class="admin-card">
                        <div class="card-body">
                            <table id="categoryTable" class="table admin-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">ID</th>
                                        <th>ชื่อหมวดหมู่</th>
                                        <th style="width: 30%;">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
                                    while ($row = $stmt->fetch()) {
                                    ?>
                                    <tr>
                                        <td class="fw-medium"><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>
                                            <a href="categories.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-warning"><i class="bi bi-pencil me-1"></i>แก้ไข</a>
                                            <a href="categories.php?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-admin-danger" 
                                               onclick="return confirm('ยืนยันการลบ?')"><i class="bi bi-trash me-1"></i>ลบ</a>
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
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json" } });
    });
</script>
</body>
</html>