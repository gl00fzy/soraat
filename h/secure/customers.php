<?php
include_once("check_login.php");
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการลูกค้า - สรอัฐ น้ำใส</title>
    
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

        /* Avatar ลูกค้า */
        .customer-avatar {
            width: 45px;
            height: 45px;
            background-color: #ffe6f2;
            color: #ec008c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .btn-contact {
            background-color: #e9ecef;
            color: #495057;
            border: none;
            transition: 0.2s;
        }
        .btn-contact:hover { background-color: #dee2e6; color: #000; }
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
                        <a class="nav-link" href="products.php">จัดการสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">จัดการออเดอร์</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="customers.php">จัดการลูกค้า</a>
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
                <h2 class="fw-bold text-secondary"><i class="bi bi-people-fill me-2"></i>รายชื่อลูกค้าสมาชิก</h2>
                <p class="text-muted small">จัดการข้อมูลและตรวจสอบสถานะสมาชิก</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-file-earmark-arrow-down"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="card card-table">
            <div class="card-body p-0">
                
                <div class="p-3 bg-light border-bottom">
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="ค้นหาชื่อ หรือเบอร์โทร..." style="max-width: 350px;">
                        <button class="btn btn-secondary" type="submit">ค้นหา</button>
                    </form>
                </div>

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col" class="ps-4">ชื่อ-นามสกุล</th>
                            <th scope="col">เบอร์โทรศัพท์</th>
                            <th scope="col">อีเมล</th>
                            <th scope="col">วันที่สมัคร</th>
                            <th scope="col" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Mock Data ตัวอย่าง
                            $mock_customers = [
                                ['id'=>101, 'name'=>'กมลชนก ใจดี', 'tel'=>'081-234-5678', 'email'=>'kamon@mail.com', 'date'=>'12/01/2566'],
                                ['id'=>102, 'name'=>'สุรชัย มั่นคง', 'tel'=>'089-999-8888', 'email'=>'sura.chai@mail.com', 'date'=>'15/02/2566'],
                                ['id'=>103, 'name'=>'วิไลวรรณ สวยงาม', 'tel'=>'090-555-4444', 'email'=>'wilai.wan@mail.com', 'date'=>'20/02/2566'],
                            ];

                            foreach($mock_customers as $c) {
                                // สร้างตัวอักษรย่อจากชื่อตัวแรก
                                $initial = mb_substr($c['name'], 0, 1, "UTF-8");
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="customer-avatar shadow-sm">
                                        <?php echo $initial; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo $c['name']; ?></div>
                                        <small class="text-muted">ID: <?php echo $c['id']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $c['tel']; ?></td>
                            <td class="text-primary"><?php echo $c['email']; ?></td>
                            <td><?php echo $c['date']; ?></td>
                            <td class="text-center">
                                <a href="customer_edit.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-contact rounded-pill px-3 me-1" title="แก้ไขข้อมูล">
                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                </a>
                                <a href="customer_del.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('ลบข้อมูลลูกค้านี้?');" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } // จบ Loop ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3 text-muted small text-end">
            * แสดงข้อมูลล่าสุด 20 รายการ
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>