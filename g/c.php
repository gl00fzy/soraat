<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>สรอัฐ น้ำใส (กอฟ)</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    /* ปรับแต่งฟอนต์เพิ่มเติม (ถ้าต้องการ) */
    body { font-family: 'Sarabun', sans-serif; }
</style>
</head>

<body>

<div class="container mt-5"> <div class="alert alert-primary text-center mb-4" role="alert">
        <h1>สรอัฐ น้ำใส 66010914018</h1>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            รายการสินค้าในระบบ
        </div>
        <div class="card-body">
            
            <table id="myTable" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead class="table-dark"> <tr>    
                        <th>Order_ID</th>
                        <th>ชื่อสินค้า</th>
                        <th>ประเภทสินค้า</th>
                        <th>วันที่</th>
                        <th>ประเทศ</th>
                        <th>จำนวนเงิน</th>
                        <th>รูปภาพ</th>
                    </tr>
                </thead>
                <tbody> <?php
                include_once("connectdb.php");
                $sql ="SELECT * FROM `popsupermarket`";
                $rs = mysqli_query($conn, $sql);
                while ($data = mysqli_fetch_array($rs)) {
                ?>
                    <tr>
                        <td><?php echo $data['p_order_id'];?></td>
                        <td><?php echo $data['p_product_name'];?></td>
                        <td><?php echo $data['p_category'];?></td>
                        <td><?php echo $data['p_date'];?></td>
                        <td><?php echo $data['p_country'];?></td>
                        <td align="right"><?php echo number_format($data['p_amount'],0);?></td>
                        <td class="text-center"> 
                            <img src="images/<?php echo $data['p_product_name'];?>.jpg" width="55" class="rounded shadow-sm">
                        </td>
                    </tr>
                <?php } 
                mysqli_close($conn);
                ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            // ออปชั่นเพิ่มเติม (แปลภาษาไทยให้ปุ่มต่างๆ)
            language: {
                "sProcessing": "กำลังดำเนินการ...",
                "sLengthMenu": "แสดง _MENU_ รายการ",
                "sZeroRecords": "ไม่พบข้อมูล",
                "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
                "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                "sSearch": "ค้นหา:",
                "oPaginate": {
                    "sFirst": "หน้าแรก",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "หน้าสุดท้าย"
                }
            }
        });
    });
</script>

</body>
</html>