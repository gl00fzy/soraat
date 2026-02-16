<?php 
require_once 'db.php'; 

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // ควรใช้ password_hash($password, PASSWORD_DEFAULT) ในงานจริง
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // เช็คว่า username ซ้ำไหม
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('Username นี้มีผู้ใช้แล้ว');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, address, role) VALUES (?, ?, ?, ?, ?, 'customer')");
        if ($stmt->execute([$username, $password, $fullname, $email, $address])) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">สมัครสมาชิกใหม่</h4>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>อีเมล</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>ที่อยู่จัดส่ง (สำหรับส่งสินค้า)</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="register" class="btn btn-success w-100">ยืนยันการสมัคร</button>
                    <div class="mt-3 text-center">
                        <a href="login.php">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>