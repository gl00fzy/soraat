<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ตรวจสอบ ID
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

// ดึงข้อมูลสินค้าเดิม
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("ไม่พบสินค้า");
}

// ดึงหมวดหมู่สำหรับ Dropdown
$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();

// --- ส่วนบันทึกการแก้ไข ---
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    
    $image_path = $product['image'];

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = "product_" . uniqid() . "." . $ext;
        $target_dir = "../uploads/";
        $upload_path = $target_dir . $new_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            if (!empty($product['image']) && file_exists("../" . $product['image'])) {
                unlink("../" . $product['image']);
            }
            $image_path = "uploads/" . $new_name;
        }
    }

    $sql = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$name, $description, $price, $stock, $category_id, $image_path, $id])) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location='products.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึก');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า: <?php echo $product['name']; ?> — MY SHOP Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="admin-body">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 admin-sidebar">
            <a class="sidebar-brand" href="dashboard.php">✦ MY SHOP</a>
            <span class="sidebar-subtitle">Admin Panel</span>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link active"><i class="bi bi-box-seam"></i>จัดการสินค้า</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link"><i class="bi bi-tags"></i>จัดการหมวดหมู่</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link"><i class="bi bi-receipt"></i>จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="bi bi-people"></i>จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link logout-link"><i class="bi bi-box-arrow-left"></i>ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 admin-main">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="admin-card admin-animate">
                        <div class="card-header">
                            <h5><i class="bi bi-pencil me-2" style="color:var(--admin-warning);"></i>แก้ไขสินค้า</h5>
                            <a href="products.php" class="btn btn-sm btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
                        </div>
                        <div class="card-body admin-form">
                            <form method="post" enctype="multipart/form-data">
                                
                                <div class="mb-3">
                                    <label class="form-label">ชื่อสินค้า</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">หมวดหมู่</label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">-- เลือกหมวดหมู่ --</option>
                                            <?php foreach($categories as $cat): ?>
                                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                                    <?php echo $cat['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ราคา</label>
                                        <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $product['price']; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">รายละเอียดสินค้า</label>
                                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">จำนวนสต็อก</label>
                                        <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">รูปภาพสินค้า</label>
                                        <div class="img-preview-area" onclick="document.getElementById('imageInput').click()">
                                            <?php if($product['image']): ?>
                                                <img id="imagePreview" src="../<?php echo $product['image']; ?>">
                                                <div class="preview-placeholder" id="previewPlaceholder" style="display:none;">
                                                    <i class="bi bi-cloud-upload"></i>
                                                    <span>คลิกเพื่อเลือกรูปภาพ</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="preview-placeholder" id="previewPlaceholder">
                                                    <i class="bi bi-cloud-upload"></i>
                                                    <span>คลิกเพื่อเลือกรูปภาพ</span>
                                                </div>
                                                <img id="imagePreview" src="" style="display:none;">
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" name="image" id="imageInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <div class="form-text">อัปโหลดใหม่เพื่อเปลี่ยนรูปเดิม (ถ้าไม่เปลี่ยนให้เว้นว่างไว้)</div>
                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" name="update" class="btn btn-admin-primary btn-lg">
                                        <i class="bi bi-check-lg me-1"></i>บันทึกการแก้ไข
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('previewPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>