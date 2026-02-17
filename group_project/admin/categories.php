<?php
require_once '../db.php';

// --- ส่วนจัดการ Logic (PHP) ---

// 1. จัดการการบันทึกข้อมูล (เพิ่ม หรือ แก้ไข)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $id = $_POST['id']; // รับค่า ID (ถ้ามีคือแก้ไข ถ้าไม่มีคือเพิ่มใหม่)

    if (!empty($name)) {
        if ($id) {
            // กรณีแก้ไข (Update)
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        } else {
            // กรณีเพิ่มใหม่ (Insert)
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
        }
        header("Location: categories.php"); // รีโหลดหน้าเพื่อเคลียร์ค่า
        exit();
    }
}

// 2. จัดการการลบ (Delete)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // เช็คก่อนว่ามีสินค้าในหมวดหมู่นี้ไหม (ถ้ามีห้ามลบ เดี๋ยวสินค้าไม่มีที่อยู่)
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
    <title>Admin - จัดการประเภทสินค้า</title>
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
                <li class="nav-item"><a href="products.php" class="nav-link text-white">จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link text-white active">จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link text-white">จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link text-danger mt-5">ออกจากระบบ</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <h2 class="mb-4">จัดการประเภทสินค้า</h2>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <?php echo $edit_data ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้าใหม่'; ?>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
                                <div class="mb-3">
                                    <label>ชื่อหมวดหมู่</label>
                                    <input type="text" name="name" class="form-control" required 
                                           value="<?php echo $edit_data['name'] ?? ''; ?>" 
                                           placeholder="เช่น เสื้อผ้า, อุปกรณ์ไอที">
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <?php echo $edit_data ? 'บันทึกการแก้ไข' : 'เพิ่มข้อมูล'; ?>
                                </button>
                                <?php if($edit_data): ?>
                                    <a href="categories.php" class="btn btn-secondary w-100 mt-2">ยกเลิก</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
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
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>
                                            <a href="categories.php?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                            <a href="categories.php?delete=<?php echo $row['id']; ?>" 
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>