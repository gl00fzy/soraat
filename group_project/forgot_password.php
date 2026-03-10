<?php
require_once 'includes/db.php';

if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $reset_error = 'รหัสผ่านใหม่ไม่ตรงกัน';
    } else {
        // เช็คว่า Username และ Email ตรงกันไหม
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            // อัปเดตรหัสผ่านใหม่
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($update_stmt->execute([$hashed_password, $user['id']])) {
                echo "<script>alert('รีเซ็ตรหัสผ่านสำเร็จ! กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่'); window.location='login.php';</script>";
                exit();
            } else {
                $reset_error = 'เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน กรุณาลองใหม่';
            }
        } else {
            $reset_error = 'Username หรือ Email ไม่ถูกต้อง';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 20; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.7; ?>s;"></div>
    <?php endfor; ?>
</div>

<div class="auth-page">
    <div class="auth-card">
        <!-- Logo -->
        <div class="text-center mb-3">
            <a href="index.php" style="text-decoration:none;">
                <span style="font-size:2.5rem;">✦</span>
            </a>
        </div>
        
        <h2 class="auth-title">ลืมรหัสผ่าน</h2>
        <p class="auth-subtitle">กรุณากรอกข้อมูลเพื่อรีเซ็ตรหัสผ่านใหม่</p>

        <?php if(isset($reset_error)): ?>
        <div class="alert" style="background:rgba(252,92,125,0.15); border:1px solid rgba(252,92,125,0.3); color:#fc5c7d; border-radius:10px; text-align:center;">
            <i class="bi bi-exclamation-triangle me-1"></i> <?php echo $reset_error; ?>
        </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label-glass"><i class="bi bi-person me-1"></i>Username</label>
                <input type="text" name="username" class="form-control form-control-glass" placeholder="กรุณากรอก Username" required>
            </div>
            <div class="mb-3">
                <label class="form-label-glass"><i class="bi bi-envelope me-1"></i>Email</label>
                <input type="email" name="email" class="form-control form-control-glass" placeholder="กรุณากรอก Email ที่ใช้สมัคร" required>
            </div>
            <div class="mb-3">
                <label class="form-label-glass"><i class="bi bi-lock me-1"></i>รหัสผ่านใหม่</label>
                <input type="password" name="new_password" class="form-control form-control-glass" placeholder="ตั้งรหัสผ่านใหม่" required>
            </div>
            <div class="mb-4">
                <label class="form-label-glass"><i class="bi bi-check-circle me-1"></i>ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="confirm_password" class="form-control form-control-glass" placeholder="กรอกรหัสผ่านใหม่อีกครั้ง" required>
            </div>
            <button type="submit" name="reset_password" class="btn btn-gradient w-100 py-3" style="font-size:1.05rem;">
                <i class="bi bi-key me-2"></i>รีเซ็ตรหัสผ่าน
            </button>
        </form>

        <hr class="auth-divider">

        <p class="text-center mb-0" style="color:var(--text-muted);">
            จำรหัสผ่านได้แล้ว? <a href="login.php" class="auth-link">เข้าสู่ระบบ</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
