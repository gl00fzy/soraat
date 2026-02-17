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
    <title><?php echo htmlspecialchars($product['name']); ?> — MY SHOP</title>
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door me-1"></i>หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php"><i class="bi bi-bag me-1"></i>ตะกร้าสินค้า</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person me-1"></i>โปรไฟล์</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบ</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container" style="padding-top:100px;">
        <div class="row gy-4">
            <!-- Product Image -->
            <div class="col-lg-6 fade-in-up">
                <div class="product-image-wrapper">
                    <img src="<?php echo $product['image'] ? $product['image'] : 'https://via.placeholder.com/600x500/1a1a2e/667eea?text=No+Image'; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 fade-in-up" style="transition-delay:0.15s;">
                <div class="glass-card p-4" style="border-radius:var(--radius-lg);">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
                            <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);text-decoration:none;">หน้าแรก</a></li>
                            <li class="breadcrumb-item" style="color:var(--text-muted);">รายละเอียดสินค้า</li>
                        </ol>
                    </nav>

                    <h2 class="mb-3" style="font-weight:700;color:var(--text-primary);"><?php echo htmlspecialchars($product['name']); ?></h2>
                    
                    <div class="mb-4" style="display:inline-block; background:linear-gradient(135deg,var(--primary),var(--accent)); padding:8px 24px; border-radius:30px;">
                        <span style="font-size:1.6rem; font-weight:800; color:#fff;">
                            <?php echo number_format($product['price'], 2); ?> ฿
                        </span>
                    </div>

                    <p style="color:var(--text-secondary); line-height:1.8; margin-bottom:30px;">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="cart_action.php?act=add&p_id=<?php echo $product['id']; ?>" class="btn btn-gradient btn-lg">
                            <i class="bi bi-bag-plus me-2"></i>หยิบใส่ตะกร้า
                        </a>
                        <a href="index.php" class="btn btn-outline-glass btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
                        </a>
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
