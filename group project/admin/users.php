<?php
require_once '../db.php';

// ลบลูกค้า
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    // ต้องระวังเรื่อง Foreign Key (ถ้าลูกค้ามีออเดอร์อาจลบไม่ได้ ให้ใช้ Soft Delete หรือจัดการ Order ก่อน)
    // ในที่นี้สมมติว่าลบได้เลย
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    header("Location: users.php");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - จัดการลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>จัดการลูกค้า</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>สิทธิ์</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM users");
                    while ($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                           <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                           <?php if($row['role'] != 'admin'): // ห้ามลบ Admin ?>
                           <a href="users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบ? ข้อมูลการสั่งซื้อของลูกค้านี้อาจหายไป')">ลบ</a>
                           <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>