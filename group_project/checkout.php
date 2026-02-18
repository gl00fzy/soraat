<?php
require_once 'db.php';

// เช็คว่าล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// เช็คว่ามีของในตะกร้าไหม
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้ (สำหรับ pre-fill ที่อยู่)
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

// ดึงข้อมูลสินค้าในตะกร้า
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($products as $product) {
    $qty = $_SESSION['cart'][$product['id']];
    $total_price += $product['price'] * $qty;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน — MY SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-step {
            display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
        }
        .step-number {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff; flex-shrink: 0;
        }
        .step-label { font-weight: 600; color: var(--text-primary); font-size: 1.1rem; }
        .payment-info-box {
            background: rgba(102,126,234,0.08); border: 1px solid rgba(102,126,234,0.2);
            border-radius: var(--radius-md); padding: 20px; text-align: center;
        }
        .bank-detail { font-size: 1.3rem; font-weight: 700; color: var(--primary); letter-spacing: 2px; }
        .slip-preview-area {
            background: rgba(255,255,255,0.04); border: 2px dashed rgba(255,255,255,0.15);
            border-radius: var(--radius-md); padding: 30px; text-align: center;
            cursor: pointer; transition: all 0.3s ease;
        }
        .slip-preview-area:hover { border-color: var(--primary); background: rgba(102,126,234,0.05); }
        .slip-preview-area img { max-width: 200px; max-height: 200px; border-radius: 10px; }
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
        <div class="ms-auto">
            <a href="cart.php" class="btn btn-outline-glass btn-sm"><i class="bi bi-arrow-left me-1"></i>กลับไปตะกร้า</a>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container" style="padding-top:100px; max-width:900px;">
        <div class="section-header" style="margin-bottom:20px;">
            <h2><i class="bi bi-credit-card me-2"></i>ชำระเงิน</h2>
            <div class="accent-line"></div>
        </div>

        <form action="checkout_save.php" method="post" enctype="multipart/form-data">
            <div class="row gy-4">
                <!-- Left Column: Shipping + Payment -->
                <div class="col-lg-7">
                    <!-- ส่วนที่ 1: ที่อยู่จัดส่ง -->
                    <div class="glass-card p-4 mb-4 fade-in-up" style="border-radius:var(--radius-lg);">
                        <div class="checkout-step">
                            <div class="step-number">1</div>
                            <div class="step-label">ที่อยู่จัดส่ง</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-glass"><i class="bi bi-person me-1"></i>ชื่อผู้รับ</label>
                            <input type="text" class="form-control form-control-glass" value="<?php echo htmlspecialchars($user['fullname']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-glass"><i class="bi bi-telephone me-1"></i>เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control form-control-glass" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                   placeholder="กรอกเบอร์โทรศัพท์">
                        </div>
                        <div class="mb-0">
                            <label class="form-label-glass"><i class="bi bi-geo-alt me-1"></i>ที่อยู่จัดส่ง <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" class="form-control form-control-glass" rows="3" required 
                                      placeholder="กรอกที่อยู่สำหรับจัดส่งสินค้า"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                    </div>

                    <!-- ส่วนที่ 2: ชำระเงิน -->
                    <div class="glass-card p-4 fade-in-up" style="border-radius:var(--radius-lg); transition-delay:0.1s;">
                        <div class="checkout-step">
                            <div class="step-number">2</div>
                            <div class="step-label">ชำระเงิน</div>
                        </div>

                        <div class="payment-info-box mb-3">
                            <p style="color:var(--text-muted); margin-bottom:8px;">โอนเงินเข้าบัญชี</p>
                            <div class="mb-2">
                                <i class="bi bi-bank me-2" style="color:var(--primary);"></i>
                                <strong style="color:var(--text-primary);">ธนาคารกสิกรไทย</strong>
                            </div>
                            <div class="bank-detail">XXX-X-XXXXX-X</div>
                            <p style="color:var(--text-secondary); margin-top:6px; margin-bottom:0;">ชื่อบัญชี: MY SHOP Co., Ltd.</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-glass"><i class="bi bi-receipt me-1"></i>แนบสลิปโอนเงิน</label>
                            <div class="slip-preview-area" onclick="document.getElementById('slipInput').click()">
                                <div id="slipPlaceholder">
                                    <i class="bi bi-cloud-upload" style="font-size:2rem; color:var(--text-muted);"></i>
                                    <p style="color:var(--text-muted); margin:8px 0 0;">คลิกเพื่อแนบสลิปโอนเงิน</p>
                                    <small style="color:var(--text-muted);">รองรับ .jpg, .png, .jpeg</small>
                                </div>
                                <img id="slipPreview" src="" style="display:none;">
                            </div>
                            <input type="file" name="payment_slip" id="slipInput" class="d-none" accept="image/*" onchange="previewSlip(this)">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-5">
                    <div class="glass-card p-4 fade-in-up" style="border-radius:var(--radius-lg); transition-delay:0.2s; position:sticky; top:100px;">
                        <div class="checkout-step">
                            <div class="step-number">3</div>
                            <div class="step-label">สรุปคำสั่งซื้อ</div>
                        </div>

                        <?php foreach ($products as $product): 
                            $qty = $_SESSION['cart'][$product['id']];
                            $subtotal = $product['price'] * $qty;
                        ?>
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            <div>
                                <div class="fw-medium" style="font-size:0.9rem;"><?php echo htmlspecialchars($product['name']); ?></div>
                                <small style="color:var(--text-muted);">x<?php echo $qty; ?></small>
                            </div>
                            <div class="gradient-text fw-bold"><?php echo number_format($subtotal, 2); ?> ฿</div>
                        </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between align-items-center pt-3 mt-2" style="border-top:2px solid rgba(102,126,234,0.3);">
                            <span class="fw-bold" style="color:var(--text-secondary);">ยอดรวมทั้งหมด</span>
                            <span class="gradient-text fw-bold" style="font-size:1.4rem;"><?php echo number_format($total_price, 2); ?> ฿</span>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 py-3 mt-4" style="font-size:1.1rem;">
                            <i class="bi bi-check2-circle me-2"></i>ยืนยันการสั่งซื้อ
                        </button>
                        
                        <p class="text-center mt-2 mb-0" style="color:var(--text-muted); font-size:0.8rem;">
                            <i class="bi bi-shield-check me-1"></i>ข้อมูลของคุณปลอดภัย
                        </p>
                    </div>
                </div>
            </div>
        </form>
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

function previewSlip(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('slipPreview').src = e.target.result;
            document.getElementById('slipPreview').style.display = 'block';
            document.getElementById('slipPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
