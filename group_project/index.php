<?php require_once 'db.php'; ?>
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

        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM products");
            $delay = 0;
            while ($row = $stmt->fetch()) {
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fade-in-up" style="transition-delay: <?php echo $delay * 0.1; ?>s;">
                <div class="product-card h-100">
                    <div class="card-img-wrapper">
                        <img src="<?php echo $row['image'] ? $row['image'] : 'https://via.placeholder.com/300x220/1a1a2e/667eea?text=No+Image'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
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