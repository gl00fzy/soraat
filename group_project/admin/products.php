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
    <title>Admin - จัดการสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
            <h4>Admin Panel</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link text-white active">จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link text-white">จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link text-white">จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link text-danger mt-5">ออกจากระบบ</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <h2 class="mb-4">จัดการสินค้า</h2>
            <div class="mb-3">
                <a href="product_add.php" class="btn btn-success">เพิ่มสินค้าใหม่</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
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
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <?php if($row['image']): ?>
                                        <img src="../<?php echo $row['image']; ?>" width="50" class="img-thumbnail">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name'] ?? '-'); ?></td>
                                <td><?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo $row['stock']; ?></td>
                                <td>
                                    <a href="product_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                    <a href="product_delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
