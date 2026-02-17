<?php require_once 'db.php'; ?>
<?php
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "ไม่พบสินค้า";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">MY SHOP</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">ตะกร้าสินค้า</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php">โปรไฟล์</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">ออกจากระบบ</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">เข้าสู่ระบบ</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $product['image'] ? $product['image'] : 'https://via.placeholder.com/500x500'; ?>" class="img-fluid rounded shadow-sm" alt="Product Image">
        </div>
        <div class="col-md-6">
            <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
            <h4 class="text-primary mb-3"><?php echo number_format($product['price'], 2); ?> ฿</h4>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <a href="cart_action.php?act=add&p_id=<?php echo $product['id']; ?>" class="btn btn-primary btn-lg mt-3">หยิบใส่ตะกร้า</a>
            <a href="index.php" class="btn btn-outline-secondary btn-lg mt-3 ms-2">กลับหน้าหลัก</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
