<?php require_once 'db.php'; ?>
<?php
// --- ดึงหมวดหมู่ทั้งหมดสำหรับ filter ---
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// --- รับค่า search + category filter ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat_id = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

// --- สร้าง SQL แบบ dynamic ---
$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($cat_id > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $cat_id;
}

$sql .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY SHOP — Premium Store</title>
    <meta name="description" content="ร้านค้าออนไลน์คุณภาพ เลือกซื้อสินค้าหลากหลายในราคาสบายกระเป๋า">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Particles Background -->
<div class="particles-bg">
    <?php for($i = 0; $i < 30; $i++): ?>
    <div class="particle" style="left:<?php echo rand(0,100); ?>%; animation-delay:<?php echo $i * 0.5; ?>s;"></div>
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
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-house-door me-1"></i>หน้าแรก</a></li>
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

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>✦ สินค้าคุณภาพ ที่คัดสรรมาเพื่อคุณ</h1>
            <p>เลือกซื้อสินค้าหลากหลาย ราคาสบายกระเป๋า จัดส่งถึงหน้าบ้าน</p>
            <a href="#products" class="btn btn-gradient btn-lg btn-hero">
                <i class="bi bi-bag-heart me-2"></i>เลือกซื้อสินค้า
            </a>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container" id="products">
        <div class="section-header">
            <h2>สินค้าแนะนำ</h2>
            <p>คัดสรรสินค้าคุณภาพดี ราคาเป็นกันเอง</p>
            <div class="accent-line"></div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <form action="index.php#products" method="get" class="d-flex gap-2">
                    <div class="input-group" style="border-radius:var(--radius-md); overflow:hidden;">
                        <span class="input-group-text" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); color:var(--primary); border-right:none;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-glass" 
                               placeholder="ค้นหาสินค้า..." value="<?php echo htmlspecialchars($search); ?>"
                               style="border-left:none;">
                    </div>

                    <button type="submit" class="btn btn-gradient px-4">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                </form>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="text-center mb-4">
            <a href="index.php<?php echo $search ? '?search='.urlencode($search) : ''; ?>#products" 
               class="btn <?php echo $cat_id == 0 ? 'btn-gradient' : 'btn-outline-glass'; ?> btn-sm m-1">
                <i class="bi bi-grid me-1"></i>ทั้งหมด
            </a>
            <?php foreach($categories as $cat): ?>
                <a href="index.php?cat=<?php echo $cat['id']; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>#products" 
                   class="btn <?php echo $cat_id == $cat['id'] ? 'btn-gradient' : 'btn-outline-glass'; ?> btn-sm m-1">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Search Results Info -->
        <?php if($search || $cat_id > 0): ?>
            <div class="text-center mb-3" style="color:var(--text-muted);">
                <?php if($search): ?>
                    <span>ผลค้นหา: "<strong style="color:var(--primary);"><?php echo htmlspecialchars($search); ?></strong>"</span>
                <?php endif; ?>
                <?php if($cat_id > 0):
                    $current_cat = '';
                    foreach($categories as $c) { if($c['id'] == $cat_id) $current_cat = $c['name']; }
                ?>
                    <span>หมวดหมู่: <strong style="color:var(--accent);"><?php echo htmlspecialchars($current_cat); ?></strong></span>
                <?php endif; ?>
                &nbsp; <a href="index.php#products" style="color:var(--primary);">✕ ล้างตัวกรอง</a>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php
            $delay = 0;
            $count = 0;
            while ($row = $stmt->fetch()) {
                $count++;
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in-up" style="transition-delay: <?php echo $delay * 0.1; ?>s;">
                <div class="product-card h-100">
                    <div class="card-img-wrapper">
                        <img src="<?php echo $row['image'] ? $row['image'] : 'https://via.placeholder.com/300x220/1a1a2e/667eea?text=No+Image'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <?php if(!empty($row['category_name'])): ?>
                            <span class="mb-1" style="font-size:0.75rem; color:var(--accent); font-weight:500;">
                                <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($row['category_name']); ?>
                            </span>
                        <?php endif; ?>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars(mb_substr($row['description'], 0, 50, 'UTF-8')) . '...'; ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price-tag"><?php echo number_format($row['price'], 2); ?> ฿</span>
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-glass btn-sm">
                                <i class="bi bi-eye me-1"></i>ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php $delay++; } ?>

            <?php if($count == 0): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size:3rem; color:var(--text-muted);"></i>
                    <h4 class="mt-3" style="color:var(--text-secondary);">ไม่พบสินค้าที่ค้นหา</h4>
                    <p style="color:var(--text-muted);">ลองเปลี่ยนคำค้นหาหรือหมวดหมู่ใหม่</p>
                    <a href="index.php" class="btn btn-gradient mt-2">ดูสินค้าทั้งหมด</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="premium-footer">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> MY SHOP — All rights reserved.</p>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Scroll-triggered fade-in animation
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
</body>
</html>