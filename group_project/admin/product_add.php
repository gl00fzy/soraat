<?php
require_once '../db.php';

// ตรวจสอบสิทธิ์ Admin (ถ้ามีระบบ Login)
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

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
    $image_path = ""; // ค่าเริ่มต้นถ้าไม่ได้อัปโหลดรูป
    
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ (เช่น product_17042023.jpg)
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = "product_" . uniqid() . "." . $ext;
        
        // กำหนดตำแหน่งที่จะเซฟไฟล์ (เซฟไปที่โฟลเดอร์ uploads หน้าบ้าน)
        $target_dir = "../uploads/"; 
        $upload_path = $target_dir . $new_name;

        // ย้ายไฟล์จาก Temp ไปที่โฟลเดอร์จริง
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // path ที่จะเก็บใน DB (ตัด ../ ออก เพื่อให้เรียกใช้จากหน้าบ้านได้ง่าย)
            $image_path = "uploads/" . $new_name;
        }
    }

    // บันทึกลงฐานข้อมูล
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
    <title>เพิ่มสินค้าใหม่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">เพิ่มสินค้าใหม่</h4>
                    <a href="products.php" class="btn btn-sm btn-light text-primary">ย้อนกลับ</a>
                </div>
                <div class="card-body">
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
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div class="form-text">รองรับไฟล์ .jpg, .png, .jpeg</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึกข้อมูล</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>