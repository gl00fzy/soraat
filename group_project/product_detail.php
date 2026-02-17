<?php require_once 'db.php'; ?>
<?php
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "ไม่พบสินค้า";
    exit();
}

// ดึงรูปเพิ่มเติมจากตาราง product_images (ถ้ามี)
$extra_images = [];
try {
    $img_stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $img_stmt->execute([$id]);
    $extra_images = $img_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // ตาราง product_images อาจยังไม่ได้สร้าง
}

// รวมรูปทั้งหมด (รูปหลัก + รูปเพิ่มเติม)
$all_images = [];
if (!empty($product['image'])) {
    $all_images[] = $product['image'];
}
foreach ($extra_images as $img) {
    $all_images[] = $img['image_path'];
}
if (empty($all_images)) {
    $all_images[] = 'https://via.placeholder.com/600x500/1a1a2e/667eea?text=No+Image';
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
    <style>
        .gallery-main-img {
            width: 100%;
            max-height: 450px;
            object-fit: contain;
            border-radius: var(--radius-md);
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            transition: opacity 0.3s ease;
        }
        .gallery-thumbs {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        .gallery-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid transparent;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s ease;
        }
        .gallery-thumb:hover, .gallery-thumb.active {
            border-color: var(--primary);
            opacity: 1;
            transform: scale(1.05);
        }
    </style>
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
            <!-- Product Image Gallery -->
            <div class="col-lg-6 fade-in-up">
                <div class="product-image-wrapper">
                    <img id="mainImage" src="<?php echo $all_images[0]; ?>" class="gallery-main-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <?php if(count($all_images) > 1): ?>
                <div class="gallery-thumbs">
                    <?php foreach($all_images as $idx => $img_path): ?>
                    <img src="<?php echo $img_path; ?>" 
                         class="gallery-thumb <?php echo $idx == 0 ? 'active' : ''; ?>" 
                         onclick="changeImage(this, '<?php echo $img_path; ?>')"
                         alt="รูปภาพ <?php echo $idx+1; ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 fade-in-up" style="transition-delay:0.15s;">
                <div class="glass-card p-4" style="border-radius:var(--radius-lg);">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
                            <li class="breadcrumb-item"><a href="index.php" style="color:var(--primary);text-decoration:none;">หน้าแรก</a></li>
                            <?php if(!empty($product['category_name'])): ?>
                            <li class="breadcrumb-item"><a href="index.php?cat=<?php echo $product['category_id']; ?>" style="color:var(--accent);text-decoration:none;"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
                            <?php endif; ?>
                            <li class="breadcrumb-item" style="color:var(--text-muted);">รายละเอียดสินค้า</li>
                        </ol>
                    </nav>

                    <h2 class="mb-2" style="font-weight:700;color:var(--text-primary);"><?php echo htmlspecialchars($product['name']); ?></h2>
                    
                    <?php if(!empty($product['category_name'])): ?>
                    <span class="mb-3 d-inline-block" style="background:rgba(102,126,234,0.12); color:var(--primary); padding:4px 14px; border-radius:20px; font-size:0.85rem;">
                        <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($product['category_name']); ?>
                    </span>
                    <?php endif; ?>

                    <div class="my-3" style="display:inline-block; background:linear-gradient(135deg,var(--primary),var(--accent)); padding:8px 24px; border-radius:30px;">
                        <span style="font-size:1.6rem; font-weight:800; color:#fff;">
                            <?php echo number_format($product['price'], 2); ?> ฿
                        </span>
                    </div>

                    <p style="color:var(--text-secondary); line-height:1.8; margin-bottom:20px;">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>

                    <div class="mb-3" style="color:var(--text-muted); font-size:0.9rem;">
                        <i class="bi bi-box-seam me-1"></i>สต็อก: 
                        <?php if($product['stock'] > 0): ?>
                            <span style="color:var(--accent); font-weight:600;">มีสินค้า (<?php echo $product['stock']; ?> ชิ้น)</span>
                        <?php else: ?>
                            <span style="color:#fc5c7d; font-weight:600;">สินค้าหมด</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <?php if($product['stock'] > 0): ?>
                        <a href="cart_action.php?act=add&p_id=<?php echo $product['id']; ?>" class="btn btn-gradient btn-lg">
                            <i class="bi bi-bag-plus me-2"></i>หยิบใส่ตะกร้า
                        </a>
                        <?php else: ?>
                        <button class="btn btn-gradient btn-lg" disabled style="opacity:0.5;">
                            <i class="bi bi-x-circle me-2"></i>สินค้าหมด
                        </button>
                        <?php endif; ?>
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

function changeImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
</body>
</html>
