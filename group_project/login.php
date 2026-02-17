<?php require_once 'db.php'; ?>
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="text-center">เข้าสู่ระบบ</h3>
    <form action="" method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        <p class="mt-2 text-center"><a href="register.php">สมัครสมาชิกใหม่</a></p>
    </form>
</div>

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
        } else {
            header("Location: index.php");
        }
    } else {
        echo "<script>alert('Username หรือ Password ไม่ถูกต้อง');</script>";
    }
}
?>