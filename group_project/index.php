<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #495057; font-family: 'Sarabun', sans-serif; }
        .card { border: none; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #6c757d; border-color: #6c757d; } /* Soft Grey Button */
        .btn-primary:hover { background-color: #5a6268; }
        .navbar { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">MY SHOP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">หน้าแรก</a></li>
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

<div class="container mt-5 pt-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center my-4">
            <h2 class="fw-light">สินค้าแนะนำ</h2>
            <p class="text-muted">เลือกซื้อสินค้าคุณภาพในราคาสบายกระเป๋า</p>
        </div>
    </div>

    <div class="row">
        <?php
        // ดึงข้อมูลสินค้าจาก Database
        $stmt = $pdo->query("SELECT * FROM products");
        while ($row = $stmt->fetch()) {
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="<?php echo $row['image'] ? $row['image'] : 'https://via.placeholder.com/300x200'; ?>" class="card-img-top" alt="Product Image">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                    <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="h5 mb-0 text-dark"><?php echo number_format($row['price'], 2); ?> ฿</span>
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>