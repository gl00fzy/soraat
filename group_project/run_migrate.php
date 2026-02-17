<?php
require_once 'db.php';

echo "<h2>Running Migration...</h2><pre>";

$queries = [
    "CREATE TABLE IF NOT EXISTS product_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )" => "สร้างตาราง product_images",
    
    "ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email" => "เพิ่มคอลัมน์ phone ใน users",
    
    "ALTER TABLE orders ADD COLUMN shipping_address TEXT AFTER status" => "เพิ่มคอลัมน์ shipping_address ใน orders",
    
    "ALTER TABLE orders ADD COLUMN payment_slip VARCHAR(255) AFTER shipping_address" => "เพิ่มคอลัมน์ payment_slip ใน orders",
    
    "ALTER TABLE orders ADD COLUMN payment_date DATETIME AFTER payment_slip" => "เพิ่มคอลัมน์ payment_date ใน orders",
];

foreach ($queries as $sql => $label) {
    try {
        $pdo->exec($sql);
        echo "✅ $label — สำเร็จ\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "⏭️ $label — มีอยู่แล้ว (ข้าม)\n";
        } else {
            echo "❌ $label — Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n</pre><h3>✅ Migration เสร็จสิ้น!</h3>";
echo "<p><a href='index.php'>กลับหน้าหลัก</a></p>";
?>
