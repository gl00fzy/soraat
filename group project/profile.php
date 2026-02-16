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
    <title>โปรไฟล์ของฉัน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">MY SHOP</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-sm">กลับหน้าหลัก</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        แก้ไขข้อมูลส่วนตัว
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" value="<?php echo $user['username']; ?>" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label>ชื่อ-นามสกุล</label>
                                <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>อีเมล</label>
                                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>ที่อยู่จัดส่ง</label>
                                <textarea name="address" class="form-control" rows="3"><?php echo $user['address']; ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary w-100">บันทึกการแก้ไข</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">ประวัติการสั่งซื้อของฉัน</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
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
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo number_format($order['total_price'], 2); ?> ฿</td>
                                    <td>
                                        <?php 
                                        // แสดงสถานะแบบมีสี
                                        $status = $order['status'];
                                        $badge_color = 'secondary';
                                        if($status == 'paid') $badge_color = 'info';
                                        if($status == 'shipped') $badge_color = 'success';
                                        if($status == 'cancelled') $badge_color = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badge_color; ?>"><?php echo ucfirst($status); ?></span>
                                    </td>
                                    <td>
                                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-info">ดูรายการ</a>
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
</body>
</html>