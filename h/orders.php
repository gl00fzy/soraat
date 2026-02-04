<?php
include_once("check_login.php");
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการออเดอร์ - สรอัฐ น้ำใส</title>
    
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
            background-color: #fff0f5; /* สีพื้นหลังหัวตารางชมพูอ่อน */
            color: #d63384;
            font-weight: 600;
        }

        .btn-view {
            background-color: #e2e6ea;
            color: #495057;
            border: none;
        }
        .btn-view:hover { background-color: #dbe2e8; }

        /* ป้ายสถานะ */
        .badge-status { font-weight: 400; padding: 8px 12px; border-radius: 30px; }
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
                        <a class="nav-link active" href="orders.php">จัดการออเดอร์</a> </li>
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
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary"><i class="bi bi-clipboard-data me-2"></i>รายการออเดอร์</h2>
            
            <form class="d-flex" role="search">
                <input class="form-control me-2 rounded-pill" type="search" placeholder="ค้นหาเลขที่ออเดอร์..." aria-label="Search">
                <button class="btn btn-outline-danger rounded-pill px-4" type="submit">ค้นหา</button>
            </form>
        </div>

        <div class="card card-table">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col" class="ps-4 py-3">#ออเดอร์</th>
                            <th scope="col">ลูกค้า</th>
                            <th scope="col">วันที่สั่งซื้อ</th>
                            <th scope="col">ยอดรวม</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // ตัวอย่างข้อมูลสมมติ (Mock Data) -- เวลาใช้จริงลบตรงนี้แล้วใส่ Loop while($row = mysqli_fetch_array($result)) แทนครับ
                            $mock_orders = [
                                ['id'=>'ORD-001', 'name'=>'สมชาย ใจดี', 'date'=>'2023-10-01', 'price'=>'1,500', 'status'=>'paid'],
                                ['id'=>'ORD-002', 'name'=>'วิภาดา รักสวย', 'date'=>'2023-10-02', 'price'=>'850', 'status'=>'pending'],
                                ['id'=>'ORD-003', 'name'=>'ณัฐวุฒิ สุดหล่อ', 'date'=>'2023-10-02', 'price'=>'2,300', 'status'=>'cancel'],
                            ];

                            foreach($mock_orders as $order) {
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-pink" style="color:#d63384;"><?php echo $order['id']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td class="text-muted"><?php echo $order['date']; ?></td>
                            <td class="fw-bold text-success">฿<?php echo $order['price']; ?></td>
                            <td>
                                <?php 
                                    if($order['status'] == 'paid') echo '<span class="badge bg-success badge-status"><i class="bi bi-check-circle me-1"></i>ชำระแล้ว</span>';
                                    else if($order['status'] == 'pending') echo '<span class="badge bg-warning text-dark badge-status"><i class="bi bi-hourglass-split me-1"></i>รอตรวจสอบ</span>';
                                    else echo '<span class="badge bg-danger badge-status"><i class="bi bi-x-circle me-1"></i>ยกเลิก</span>';
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-view rounded-pill px-3">
                                    <i class="bi bi-eye"></i> ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                        <?php } // จบ Loop ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">ก่อนหน้า</a></li>
                <li class="page-item active"><a class="page-link bg-danger border-danger" href="#">1</a></li>
                <li class="page-item"><a class="page-link text-danger" href="#">2</a></li>
                <li class="page-item"><a class="page-link text-danger" href="#">3</a></li>
                <li class="page-item"><a class="page-link text-danger" href="#">ถัดไป</a></li>
            </ul>
        </nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>