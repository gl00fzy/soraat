<?php
require_once 'db.php';

// ดึง users ทั้งหมดที่ password ยังไม่ได้ hash (hash ของ bcrypt จะขึ้นต้นด้วย $2y$)
$stmt = $pdo->query("SELECT id, username, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>แก้ไข Password ให้เป็น Hash</h2><pre>";

$count = 0;
foreach ($users as $user) {
    // เช็คว่า password ยังไม่ได้ hash (ไม่ขึ้นต้นด้วย $2y$)
    if (strpos($user['password'], '$2y$') !== 0) {
        $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $user['id']]);
        echo "✅ User: {$user['username']} — Password ถูก hash แล้ว\n";
        $count++;
    } else {
        echo "⏭️ User: {$user['username']} — Password เป็น hash อยู่แล้ว (ข้าม)\n";
    }
}

echo "\n</pre>";
echo "<h3>อัปเดตแล้ว $count รายการ</h3>";
echo "<p><strong style='color:red;'>⚠️ แนะนำให้ลบไฟล์นี้ทิ้งหลังใช้งาน เพื่อความปลอดภัย</strong></p>";
echo "<p><a href='login.php'>ไปหน้า Login</a></p>";
?>
