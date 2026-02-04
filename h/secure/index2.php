<?php
include_once("check_login.php"); 
// ตรวจสอบว่ามีไฟล์ check_login.php จริงหรือไม่ ถ้ายังไม่มีให้สร้างไฟล์นี้เพื่อเช็ค session นะครับ
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก Admin - สรอัฐ น้ำใส</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f8f9fa;
            /* พื้นหลังสีอ่อนๆ ตัดกับ Navbar */
            min-height: 100vh;
        }

        /* Navbar โทนชมพูไล่เฉด */
        .navbar-custom {
            background: linear-gradient(to right, #ec008c, #fc6767);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .nav-link-user {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 400;
        }

        /* การ์ดเมนู */
        .menu-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        /* เอฟเฟกต์ตอนเอาเมาส์ชี้ */
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(236, 0, 140, 0.2);
        }

        .menu-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #ec008c, #fc6767);
        }

        .icon-box {
            font-size: 3rem;
            margin-bottom: 15px;
            background: -webkit-linear-gradient(#ec008c, #fc6767);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-title {
            color: #444;
            font-weight: 600;
        }

        .card-text {
            color: #888;
            font-size: 0.9rem;
        }
        
        a.card-link-wrapper {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom mb-5">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-gem me-2"></i>สรอัฐ น้ำใส</a>
            
            <div class="d-flex align-items-center">
                <span class="nav-link-user me-3">
                    <i class="bi bi-person-circle me-1"></i> 
                    สวัสดี, คุณ <?php echo isset($_SESSION['aname']) ? $_SESSION['aname'] : 'Admin'; ?>
                </span>
                <a href="logout.php" class="btn btn-light btn-sm rounded-pill px-3 text-pink fw-bold" style="color: #ec008c;">
                    <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-secondary">แผงควบคุมหลัก</h2>
                <p class="text-muted">เลือกเมนูที่ต้องการจัดการ</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-md-4">
                <a href="products.php" class="card-link-wrapper">
                    <div class="card menu-card text-center p-4">
                        <div class="card-body">
                            <div class="icon-box">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                            <h4 class="card-title">จัดการสินค้า</h4>
                            <p class="card-text">เพิ่ม ลบ แก้ไข รายการสินค้าในสต็อก</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="orders.php" class="card-link-wrapper">
                    <div class="card menu-card text-center p-4">
                        <div class="card-body">
                            <div class="icon-box">
                                <i class="bi bi-clipboard-data-fill"></i>
                            </div>
                            <h4 class="card-title">จัดการออเดอร์</h4>
                            <p class="card-text">ตรวจสอบคำสั่งซื้อและสถานะการจัดส่ง</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="customers.php" class="card-link-wrapper">
                    <div class="card menu-card text-center p-4">
                        <div class="card-body">
                            <div class="icon-box">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h4 class="card-title">จัดการลูกค้า</h4>
                            <p class="card-text">ดูข้อมูลสมาชิกและประวัติลูกค้า</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>