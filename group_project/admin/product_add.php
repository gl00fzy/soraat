<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ดึงหมวดหมู่สินค้ามาเตรียมไว้สำหรับ Dropdown
$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    
    // --- จัดการรูปภาพ ---
    $image_path = "";
    
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = "product_" . uniqid() . "." . $ext;
        
        $target_dir = "../uploads/"; 
        $upload_path = $target_dir . $new_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = "uploads/" . $new_name;
        }
    }

    $sql = "INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$name, $description, $price, $stock, $category_id, $image_path])) {
        echo "<script>alert('เพิ่มสินค้าเรียบร้อย'); window.location='products.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้าใหม่ — MY SHOP Admin</title>
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
                            <h5><i class="bi bi-plus-circle me-2" style="color:var(--admin-primary);"></i>เพิ่มสินค้าใหม่</h5>
                            <a href="products.php" class="btn btn-sm btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
                        </div>
                        <div class="card-body admin-form">
                            <form method="post" enctype="multipart/form-data">
                                
                                <div class="mb-3">
                                    <label class="form-label">ชื่อสินค้า <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">-- เลือกหมวดหมู่ --</option>
                                            <?php foreach($categories as $cat): ?>
                                                <option value="<?php echo $cat['id']; ?>">
                                                    <?php echo $cat['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ราคา (บาท) <span class="text-danger">*</span></label>
                                        <input type="number" name="price" step="0.01" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">รายละเอียดสินค้า</label>
                                    <textarea name="description" class="form-control" rows="4"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">จำนวนสต็อก <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control" value="10" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">รูปภาพสินค้า</label>
                                        <div class="img-preview-area" onclick="document.getElementById('imageInput').click()">
                                            <div class="preview-placeholder" id="previewPlaceholder">
                                                <i class="bi bi-cloud-upload"></i>
                                                <span>คลิกเพื่อเลือกรูปภาพ</span>
                                            </div>
                                            <img id="imagePreview" src="" style="display:none;">
                                        </div>
                                        <input type="file" name="image" id="imageInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <div class="form-text">รองรับไฟล์ .jpg, .png, .jpeg</div>
                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" name="submit" class="btn btn-admin-success btn-lg">
                                        <i class="bi bi-check-lg me-1"></i>บันทึกข้อมูล
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