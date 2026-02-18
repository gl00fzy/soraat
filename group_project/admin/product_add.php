<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// ดึงข้อมูลหมวดหมู่สำหรับ dropdown
$stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY name");

// --- ส่วนบันทึกข้อมูล ---
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $image_path = '';

    // 1. อัปโหลดรูปหลัก
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('prod_') . "." . $ext;
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        chmod($target_dir, 0777); // ให้สิทธิ์เขียนโฟลเดอร์
        $upload_path = $target_dir . $new_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = "uploads/" . $new_name;
        }
    }

    // 2. บันทึกสินค้าหลัก
    $sql = "INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description, $price, $stock, $category_id, $image_path]);
    $product_id = $pdo->lastInsertId();

    // 3. อัปโหลดรูปเพิ่มเติม (multiple)
    if (!empty($_FILES['extra_images']['name'][0])) {
        try {
            $target_dir = "../uploads/";
            foreach ($_FILES['extra_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['extra_images']['error'][$key] == 0) {
                    $ext = pathinfo($_FILES['extra_images']['name'][$key], PATHINFO_EXTENSION);
                    $new_name = uniqid('extra_') . "." . $ext;
                    $upload_path = $target_dir . $new_name;
                    if (move_uploaded_file($tmp_name, $upload_path)) {
                        $img_path = "uploads/" . $new_name;
                        $stmt_img = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                        $stmt_img->execute([$product_id, $img_path]);
                    }
                }
            }
        } catch (PDOException $e) {
            // product_images table อาจยังไม่มี — ข้ามไป
        }
    }

    echo "<script>alert('เพิ่มสินค้าสำเร็จ!'); window.location='products.php';</script>";
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
    <style>
        .preview-img {
            max-width: 200px; max-height: 200px; border-radius: 12px;
            border: 2px solid var(--admin-card-border);
        }
        .extra-preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .extra-preview-container img {
            width: 80px; height: 80px; object-fit: cover; border-radius: 8px;
            border: 1px solid var(--admin-card-border);
        }
    </style>
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
                            <h5><i class="bi bi-plus-circle me-2" style="color:var(--admin-success);"></i>เพิ่มสินค้าใหม่</h5>
                            <a href="products.php" class="btn btn-sm btn-admin-secondary"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
                        </div>
                        <div class="card-body admin-form">
                            <form method="post" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label class="form-label">ชื่อสินค้า <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="ใส่ชื่อสินค้า">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">รายละเอียดสินค้า</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="อธิบายรายละเอียดสินค้า"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">ราคา (฿) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">จำนวนสต็อก</label>
                                        <input type="number" name="stock" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">หมวดหมู่</label>
                                        <select name="category_id" class="form-select">
                                            <option value="">-- เลือกหมวดหมู่ --</option>
                                            <?php while($cat = $stmt_cat->fetch()): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-image me-1"></i>รูปภาพหลัก</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewMain(this)">
                                    <div class="mt-2" id="mainPreview"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-images me-1"></i>รูปภาพเพิ่มเติม (เลือกได้หลายรูป)</label>
                                    <input type="file" name="extra_images[]" class="form-control" accept="image/*" multiple onchange="previewExtra(this)">
                                    <div class="extra-preview-container" id="extraPreview"></div>
                                    <div class="form-text">กดค้าง Ctrl แล้วเลือกหลายไฟล์</div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" name="save" class="btn btn-admin-success btn-lg">
                                        <i class="bi bi-check-lg me-1"></i>บันทึกสินค้า
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
function previewMain(input) {
    const preview = document.getElementById('mainPreview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" class="preview-img">`; };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewExtra(input) {
    const container = document.getElementById('extraPreview');
    container.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => { container.innerHTML += `<img src="${e.target.result}">`; };
            reader.readAsDataURL(file);
        });
    }
}
</script>
</body>
</html>