<?php
require_once 'db.php';

// เช็คล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- ส่วนจัดการแก้ไขข้อมูล ---
if (isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    $sql = "UPDATE users SET fullname=?, email=?, address=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$fullname, $email, $address, $user_id])) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ');</script>";
        // อัปเดตข้อมูลใหม่เพื่อแสดงผลทันที
        $_SESSION['fullname'] = $fullname; 
    }
}

// ดึงข้อมูลผู้ใช้ปัจจุบัน
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

// ดึงประวัติการสั่งซื้อ
$stmt_orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt_orders->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 15; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.8; ?>s;"></div>
    <?php endfor; ?>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top premium-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">✦ MY SHOP</a>
        <div class="ms-auto d-flex gap-2">
            <a href="index.php" class="btn btn-outline-glass btn-sm"><i class="bi bi-house-door me-1"></i>กลับหน้าหลัก</a>
            <a href="logout.php" class="btn btn-danger-glass btn-sm"><i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container" style="padding-top:100px;">
        <div class="row gy-4">
            <!-- Profile Card -->
            <div class="col-lg-4 fade-in-up">
                <div class="glass-card" style="overflow:hidden; border-radius:var(--radius-lg);">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h5 class="text-white mb-1"><?php echo htmlspecialchars($user['fullname']); ?></h5>
                        <small style="color:rgba(255,255,255,0.7);">@<?php echo htmlspecialchars($user['username']); ?></small>
                    </div>
                    <div class="p-4">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label-glass"><i class="bi bi-person me-1"></i>Username</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control form-control-glass" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-glass"><i class="bi bi-person-badge me-1"></i>ชื่อ-นามสกุล</label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" class="form-control form-control-glass" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-glass"><i class="bi bi-envelope me-1"></i>อีเมล</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control form-control-glass">
                            </div>
                            <div class="mb-3">
                                <label class="form-label-glass"><i class="bi bi-geo-alt me-1"></i>ที่อยู่จัดส่ง</label>
                                <textarea name="address" class="form-control form-control-glass" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-gradient w-100">
                                <i class="bi bi-check-lg me-1"></i>บันทึกการแก้ไข
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="col-lg-8 fade-in-up" style="transition-delay:0.15s;">
                <div class="glass-card" style="border-radius:var(--radius-lg);">
                    <div class="p-4 d-flex align-items-center gap-2" style="border-bottom:1px solid var(--glass-border);">
                        <i class="bi bi-clock-history" style="color:var(--primary);font-size:1.2rem;"></i>
                        <h5 class="mb-0" style="font-weight:600;">ประวัติการสั่งซื้อของฉัน</h5>
                    </div>
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-glass mb-0">
                                <thead>
                                    <tr>
                                        <th>รหัสสั่งซื้อ</th>
                                        <th>วันที่</th>
                                        <th>ยอดรวม</th>
                                        <th>สถานะ</th>
                                        <th>รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $stmt_orders->fetch()) { ?>
                                    <tr>
                                        <td class="fw-medium">#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                        <td class="gradient-text fw-bold"><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                                        <td>
                                            <?php 
                                            $status = $order['status'];
                                            $badge_class = 'badge-pending';
                                            if($status == 'paid') $badge_class = 'badge-paid';
                                            if($status == 'shipped') $badge_class = 'badge-shipped';
                                            if($status == 'cancelled') $badge_class = 'badge-cancelled';
                                            ?>
                                            <span class="badge-glass <?php echo $badge_class; ?>"><?php echo ucfirst($status); ?></span>
                                        </td>
                                        <td>
                                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-glass">
                                                <i class="bi bi-eye me-1"></i>ดูรายการ
                                            </a>
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

    <footer class="premium-footer">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> MY SHOP — All rights reserved.</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.fade-in-up').forEach(el => {
    setTimeout(() => el.classList.add('visible'), 100);
});
</script>
</body>
</html>