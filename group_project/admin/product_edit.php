<?php
require_once '../db.php';

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
    
    // เริ่มต้นใช้รูปเดิม
    $image_path = $product['image'];

    // เช็คว่ามีการอัปโหลดรูปใหม่หรือไม่
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = "product_" . uniqid() . "." . $ext;
        $target_dir = "../uploads/";
        $upload_path = $target_dir . $new_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // ลบรูปเก่าทิ้ง (ถ้ามี และไฟล์นั้นมีอยู่จริง)
            if (!empty($product['image']) && file_exists("../" . $product['image'])) {
                unlink("../" . $product['image']);
            }
            // อัปเดต Path รูปใหม่
            $image_path = "uploads/" . $new_name;
        }
    }

    // อัปเดตข้อมูลลงฐานข้อมูล
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
    <title>แก้ไขสินค้า: <?php echo $product['name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">แก้ไขสินค้า</h4>
                    <a href="products.php" class="btn btn-sm btn-light">ย้อนกลับ</a>
                </div>
                <div class="card-body">
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
                                <?php if($product['image']): ?>
                                    <div class="mb-2">
                                        <img src="../<?php echo $product['image']; ?>" width="100" class="img-thumbnail">
                                        <small class="text-muted d-block">รูปปัจจุบัน</small>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div class="form-text">อัปโหลดใหม่เพื่อเปลี่ยนรูปเดิม (ถ้าไม่เปลี่ยนให้เว้นว่างไว้)</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update" class="btn btn-warning btn-lg">บันทึกการแก้ไข</button>
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