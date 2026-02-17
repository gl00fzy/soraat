<?php 
require_once 'db.php'; 

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // เช็คว่า username ซ้ำไหม
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    
    if ($check->fetchColumn() > 0) {
        $reg_error = 'Username นี้มีผู้ใช้แล้ว';
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, address, role) VALUES (?, ?, ?, ?, ?, 'customer')");
        if ($stmt->execute([$username, $password, $fullname, $email, $address])) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login.php';</script>";
        } else {
            $reg_error = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 20; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.7; ?>s;"></div>
    <?php endfor; ?>
</div>

<div class="auth-page">
    <div class="auth-card wide">
        <!-- Logo -->
        <div class="text-center mb-3">
            <a href="index.php" style="text-decoration:none;">
                <span style="font-size:2.5rem;">✦</span>
            </a>
        </div>
        
        <h2 class="auth-title">สมัครสมาชิกใหม่</h2>
        <p class="auth-subtitle">สร้างบัญชีใหม่เพื่อเริ่มต้นช้อปปิ้ง</p>

        <?php if(isset($reg_error)): ?>
        <div class="alert" style="background:rgba(252,92,125,0.15); border:1px solid rgba(252,92,125,0.3); color:#fc5c7d; border-radius:10px; text-align:center;">
            <i class="bi bi-exclamation-triangle me-1"></i> <?php echo $reg_error; ?>
        </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-glass"><i class="bi bi-person me-1"></i>Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control form-control-glass" placeholder="เช่น john_doe" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label-glass"><i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-glass" placeholder="ตั้งรหัสผ่าน" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-glass"><i class="bi bi-person-badge me-1"></i>ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" class="form-control form-control-glass" placeholder="ชื่อ นามสกุล" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label-glass"><i class="bi bi-envelope me-1"></i>อีเมล</label>
                    <input type="email" name="email" class="form-control form-control-glass" placeholder="example@mail.com">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label-glass"><i class="bi bi-geo-alt me-1"></i>ที่อยู่จัดส่ง <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control form-control-glass" rows="3" placeholder="กรอกที่อยู่สำหรับจัดส่งสินค้า" required></textarea>
            </div>
            <button type="submit" name="register" class="btn btn-gradient w-100 py-3" style="font-size:1.05rem;">
                <i class="bi bi-person-plus me-2"></i>ยืนยันการสมัคร
            </button>
        </form>

        <hr class="auth-divider">

        <p class="text-center mb-0" style="color:var(--text-muted);">
            มีบัญชีอยู่แล้ว? <a href="login.php" class="auth-link">เข้าสู่ระบบ</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>