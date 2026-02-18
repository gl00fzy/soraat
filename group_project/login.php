<?php require_once 'db.php'; ?>
<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // เช็ค Password แบบ Hash
    if ($user && password_verify($password, $user['password'])) { 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];
        
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        $login_error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — MY SHOP</title>
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
    <div class="auth-card">
        <!-- Logo -->
        <div class="text-center mb-3">
            <a href="index.php" style="text-decoration:none;">
                <span style="font-size:2.5rem;">✦</span>
            </a>
        </div>
        
        <h2 class="auth-title">เข้าสู่ระบบ</h2>
        <p class="auth-subtitle">ยินดีต้อนรับกลับมา! กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>

        <?php if(isset($login_error)): ?>
        <div class="alert" style="background:rgba(252,92,125,0.15); border:1px solid rgba(252,92,125,0.3); color:#fc5c7d; border-radius:10px; text-align:center;">
            <i class="bi bi-exclamation-triangle me-1"></i> Username หรือ Password ไม่ถูกต้อง
        </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label-glass"><i class="bi bi-person me-1"></i>Username</label>
                <input type="text" name="username" class="form-control form-control-glass" placeholder="กรุณากรอก Username" required>
            </div>
            <div class="mb-4">
                <label class="form-label-glass"><i class="bi bi-lock me-1"></i>Password</label>
                <input type="password" name="password" class="form-control form-control-glass" placeholder="กรุณากรอก Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-gradient w-100 py-3" style="font-size:1.05rem;">
                <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
            </button>
        </form>

        <hr class="auth-divider">

        <p class="text-center mb-0" style="color:var(--text-muted);">
            ยังไม่มีบัญชี? <a href="register.php" class="auth-link">สมัครสมาชิกใหม่</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>