<?php
require_once '../db.php';

// โค้ดอัปเดตสถานะ (เมื่อกดปุ่มบันทึกจาก Modal หรือ Form)
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    echo "<script>alert('อัปเดตสถานะเรียบร้อย'); window.location='orders.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - จัดการออเดอร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
            <h4>Admin Panel</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link text-white">จัดการสินค้า</a></li>
                <li class="nav-item"><a href="orders.php" class="nav-link text-white active">จัดการออเดอร์</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">จัดการลูกค้า</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link text-danger mt-5">ออกจากระบบ</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <h2 class="mb-4">รายการคำสั่งซื้อ</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table id="orderTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>ลูกค้า</th>
                                <th>ยอดรวม</th>
                                <th>วันที่สั่ง</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Join ตารางเพื่อดึงชื่อลูกค้ามาแสดง
                            $sql = "SELECT o.*, u.fullname FROM orders o 
                                    JOIN users u ON o.user_id = u.id 
                                    ORDER BY o.order_date DESC";
                            $stmt = $pdo->query($sql);
                            
                            while ($row = $stmt->fetch()) {
                                // กำหนดสี Badge ตามสถานะ
                                $badge_color = match($row['status']) {
                                    'pending' => 'secondary',
                                    'paid' => 'info',
                                    'shipped' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                <td><span class="badge bg-<?php echo $badge_color; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td>
                                    <a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                                    
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $row['id']; ?>">
                                        เปลี่ยนสถานะ
                                    </button>

                                    <div class="modal fade" id="statusModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form method="post" class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">อัปเดตสถานะ Order #<?php echo $row['id']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending (รอชำระ)</option>
                                                        <option value="paid" <?php if($row['status']=='paid') echo 'selected'; ?>>Paid (ชำระแล้ว)</option>
                                                        <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>Shipped (จัดส่งแล้ว)</option>
                                                        <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled (ยกเลิก)</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="update_status" class="btn btn-primary">บันทึก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#orderTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json" } });
    });
</script>
</body>
</html>