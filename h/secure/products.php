<?php
include_once("check_login.php");
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสินค้า - สรอัฐ น้ำใส</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar โทนชมพู */
        .navbar-custom {
            background: linear-gradient(to right, #ec008c, #fc6767);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand { font-weight: 600; color: white !important; }
        .nav-link { color: rgba(255,255,255,0.8) !important; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: white !important; font-weight: 500; }
        
        /* Card ตาราง */
        .card-table {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header-custom {
            background-color: #fff0f5;
            color: #d63384;
            font-weight: 600;
        }

        /* ปุ่ม Action */
        .btn-edit { background-color: #ffc107; border: none; color: #000; }
        .btn-edit:hover { background-color: #ffca2c; }
        
        .btn-delete { background-color: #dc3545; border: none; color: white; }
        .btn-delete:hover { background-color: #bb2d3b; }

        .btn-add-new {
            background: linear-gradient(45deg, #11998e, #38ef7d); /* สีเขียวสดใส */
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4);
            transition: all 0.3s;
        }
        .btn-add-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 239, 125, 0.6);
            color: white;
        }

        /* รูปสินค้า */
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-gem me-2"></i>สรอัฐ น้ำใส</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index2.php">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">จัดการสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">จัดการออเดอร์</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers.php">จัดการลูกค้า</a>
                    </li>
                </ul>
                <div class="d-flex text-white align-items-center">
                    <span class="me-3"><i class="bi bi-person-circle"></i> <?php echo $_SESSION['aname']; ?></span>
                    <a href="logout.php" class="btn btn-sm btn-light text-pink rounded-pill fw-bold" style="color: #ec008c;">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold text-secondary"><i class="bi bi-box-seam me-2"></i>รายการสินค้าทั้งหมด</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="product_form.php" class="btn btn-add-new rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> เพิ่มสินค้าใหม่
                </a>
            </div>
        </div>

        <div class="card card-table">
            <div class="card-body p-0">
                
                <div class="p-3 bg-light border-bottom">
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="ค้นหาชื่อสินค้า..." style="max-width: 300px;">
                        <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
                    </form>
                </div>

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col" class="ps-4">รูปภาพ</th>
                            <th scope="col">ชื่อสินค้า</th>
                            <th scope="col">หมวดหมู่</th>
                            <th scope="col">ราคา</th>
                            <th scope="col" class="text-center">คงเหลือ</th>
                            <th scope="col" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Mock Data สมมติ
                            $mock_products = [
                                ['id'=>1, 'name'=>'ครีมหน้าใส สูตร 1', 'cate'=>'สกินแคร์', 'price'=>'590', 'stock'=>120, 'img'=>'https://via.placeholder.com/50'],
                                ['id'=>2, 'name'=>'เซรั่มวิตามินซี', 'cate'=>'สกินแคร์', 'price'=>'890', 'stock'=>45, 'img'=>'https://via.placeholder.com/50'],
                                ['id'=>3, 'name'=>'สบู่สมุนไพร', 'cate'=>'ทำความสะอาด', 'price'=>'120', 'stock'=>5, 'img'=>'https://via.placeholder.com/50'],
                            ];

                            foreach($mock_products as $p) {
                        ?>
                        <tr>
                            <td class="ps-4">
                                <img src="<?php echo $p['img']; ?>" alt="Product" class="product-img">
                            </td>
                            <td class="fw-bold text-dark"><?php echo $p['name']; ?></td>
                            <td class="text-muted small"><?php echo $p['cate']; ?></td>
                            <td class="text-pink fw-bold" style="color: #ec008c;">฿<?php echo $p['price']; ?></td>
                            <td class="text-center">
                                <?php if($p['stock'] < 10) { ?>
                                    <span class="badge bg-danger rounded-pill">เหลือ <?php echo $p['stock']; ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-success rounded-pill"><?php echo $p['stock']; ?></span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <a href="product_edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-edit rounded px-2" title="แก้ไข">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="product_delete.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-delete rounded px-2" onclick="return confirm('ยืนยันการลบสินค้านี้?');" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } // จบ Loop ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>