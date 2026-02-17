<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ดึงข้อมูลสินค้า
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - จัดการสินค้า — MY SHOP</title>
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
                <li class="nav-item"><a href="products.php" class="nav-link active"><i class="bi bi-box-seam"></i>จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link"><i class="bi bi-tags"></i>จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="admin-page-title mb-0"><i class="bi bi-box-seam"></i>จัดการสินค้า</h2>
                <a href="product_add.php" class="btn btn-admin-success"><i class="bi bi-plus-lg me-1"></i>เพิ่มสินค้าใหม่</a>
            </div>

            <div class="admin-card admin-animate">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>หมวดหมู่</th>
                                    <th>ราคา</th>
                                    <th>สต็อก</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $stmt->fetch()) { ?>
                                <tr>
                                    <td class="fw-medium"><?php echo $row['id']; ?></td>
                                    <td>
                                        <?php if($row['image']): ?>
                                            <img src="../<?php echo $row['image']; ?>" width="44" style="border-radius:8px; border:1px solid var(--admin-card-border);">
                                        <?php else: ?>
                                            <span style="color:var(--admin-text-muted);">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><span style="color:var(--admin-text-secondary);"><?php echo htmlspecialchars($row['category_name'] ?? '—'); ?></span></td>
                                    <td class="fw-bold" style="color:var(--admin-success);"><?php echo number_format($row['price'], 2); ?></td>
                                    <td>
                                        <?php if($row['stock'] <= 5): ?>
                                        <span style="color:var(--admin-danger); font-weight:600;"><?php echo $row['stock']; ?></span>
                                        <?php else: ?>
                                        <?php echo $row['stock']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="product_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-admin-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="product_delete.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-admin-danger" 
                                           onclick="return confirm('ยืนยันการลบ?')"><i class="bi bi-trash"></i></a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
