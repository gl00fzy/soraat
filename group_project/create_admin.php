<?php
require_once 'db.php';

// ตั้งค่า Username / Password ของ Admin ที่ต้องการสร้าง
$username = 'admin';
$password = 'admin1234'; 
$fullname = 'Administrator';
$email = 'admin@example.com';
$address = 'Office';

// Hash Password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // เช็คก่อนว่ามี user นี้หรือยัง
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    
    if ($check->fetchColumn() > 0) {
        echo "Username '$username' already exists.<br>";
        
        // ถ้ามีแล้ว อาจจะอัปเดตให้เป็น admin ก็ได้
        $update = $pdo->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
        $update->execute([$username]);
        echo "Updated '$username' role to 'admin'.<br>";
        
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, address, role) VALUES (?, ?, ?, ?, ?, 'admin')");
        if ($stmt->execute([$username, $hashed_password, $fullname, $email, $address])) {
            echo "Admin user '$username' created successfully!<br>";
            echo "Password: $password<br>";
        } else {
            echo "Error creating admin user.<br>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
