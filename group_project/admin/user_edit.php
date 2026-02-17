<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ตรวจสอบ ID
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = $_GET['id'];

// ดึงข้อมูลผู้ใช้
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

// --- ส่วนบันทึกการแก้ไข ---
if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // ลอง update phone ด้วย (ถ้า column มี)
    try {
        $phone = $_POST['phone'] ?? '';
        $sql = "UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fullname, $email, $phone, $address, $id]);
    } catch (PDOException $e) {
        // ถ้า phone column ยังไม่มี
        $sql = "UPDATE users SET fullname=?, email=?, address=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fullname, $email, $address, $id]);
    }

    echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.location='users.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลลูกค้า — MY SHOP Admin</title>
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
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link active"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="admin-card admin-animate">
                        <div class="card-header">
                            <h5><i class="bi bi-pencil me-2" style="color:var(--admin-warning);"></i>แก้ไขข้อมูลลูกค้า</h5>
                            <a href="users.php" class="btn btn-sm btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
                        </div>
                        <div class="card-body admin-form">
                            <form method="post">

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-person me-1"></i>Username</label>
                                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control" disabled>
                                    <div class="form-text">Username ไม่สามารถแก้ไขได้</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-person-badge me-1"></i>ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                        <input type="text" name="fullname" class="form-control" required
                                               value="<?php echo htmlspecialchars($user['fullname']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-envelope me-1"></i>อีเมล</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-telephone me-1"></i>เบอร์โทรศัพท์</label>
                                    <input type="text" name="phone" class="form-control" 
                                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                           placeholder="เบอร์โทรศัพท์">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-geo-alt me-1"></i>ที่อยู่</label>
                                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-shield me-1"></i>สิทธิ์</label>
                                    <input type="text" value="<?php echo ucfirst($user['role']); ?>" class="form-control" disabled>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" name="update" class="btn btn-admin-primary btn-lg">
                                        <i class="bi bi-check-lg me-1"></i>บันทึกการแก้ไข
                                    </button>
                                </div>

                            </form>
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
