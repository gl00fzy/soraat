<?php
require_once 'db.php';

echo "<h1>Starting Product & Category Installation...</h1>";

try {
    // ปิดการตรวจสอบ Foreign Key ชั่วคราวเพื่อป้องกัน Error ตอนลบตาราง
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    // สร้างตาราง Categories (ลบของเก่าถ้ามี)
    $pdo->exec("DROP TABLE IF EXISTS `categories`");
    $pdo->exec("
        CREATE TABLE `categories` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
    ");

    // ใส่ข้อมูล Categories
    $pdo->exec("
        INSERT INTO `categories` (`id`, `name`) VALUES 
        (1,'Test Category'),(2,'เสื้อผ้าแฟชั่น'),(3,'อุปกรณ์ไอที'),
        (4,'ความงามและสุขภาพ'),(5,'อาหารและเครื่องดื่ม'),(6,'เครื่องใช้ในบ้าน')
    ");
    echo "✔ ตารางหมวดหมู่ (Categories) สร้างและเพิ่มข้อมูลสำเร็จ<br>";

    // สร้างตาราง Products (ลบของเก่าถ้ามี)
    $pdo->exec("DROP TABLE IF EXISTS `products`");
    $pdo->exec("
        CREATE TABLE `products` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `description` text DEFAULT NULL,
          `price` decimal(10,2) NOT NULL,
          `stock` int(11) DEFAULT 0,
          `category_id` int(11) DEFAULT NULL,
          `image` varchar(255) DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          KEY `category_id` (`category_id`),
          CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
    ");

    // ใส่ข้อมูล Products
    $pdo->exec("
        INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `image`, `created_at`) VALUES 
        (2,'เสื้อยืด Cotton 100% ลายมินิมอล','เสื้อยืดเนื้อผ้านุ่ม สวมใส่สบาย ระบายอากาศได้ดีเยี่ยม เหมาะสำหรับใส่ในทุกโอกาส',250.00,100,2,'uploads/tshirt_image_1773129457269.png','2026-03-10 07:52:32'),
        (3,'กางเกงยีนส์ทรงขากระบอก สไตล์เกาหลี','กางเกงยีนส์แฟชั่นยอดฮิต เข้าทรงสวย ผ้าทนทาน สวมใส่สบายไม่อึดอัด',590.00,50,2,'uploads/jeans_image_1773129469944.png','2026-03-10 07:52:32'),
        (4,'หูฟังไร้สาย Bluetooth 5.3','หูฟัง TWS เสียงใส เบสหนัก แบตเตอรี่อึดใช้งานต่อเนื่อง 24 ชั่วโมง มีระบบตัดเสียงรบกวน',1290.00,30,3,'uploads/earbuds_image_1773129483169.png','2026-03-10 07:52:32'),
        (5,'พาวเวอร์แบงค์ 20000mAh ชาร์จเร็ว','แบตสำรองความจุสูง รองรับ PD Fast Charge น้ำหนักเบา พกพาพกขึ้นเครื่องได้',850.00,45,3,'uploads/powerbank_image_1773129496151.png','2026-03-10 07:52:32'),
        (6,'เซรั่มบำรุงผิวหน้าใส 30ml','เซรั่มวิตามินซีเข้มข้น ช่วยกู้ผิวโทรมให้กลับมากระจ่างใส ลดรอยสิวและจุดด่างดำ',690.00,80,4,'uploads/serum_image_1773129509623.png','2026-03-10 07:52:32'),
        (7,'ครีมกันแดด SPF50+ PA++++','ครีมกันแดดเนื้อบางเบา ซึมไว ไม่เหนอะหนะ ปกป้องผิวจาก UVA และ UVB เต็มประสิทธิภาพ',450.00,70,4,'uploads/sunscreen_image_1773129538454.png','2026-03-10 07:52:32'),
        (8,'เมล็ดกาแฟอาราบิก้าคั่วกลาง 250g','เมล็ดกาแฟคุณภาพพรีเมียม หอมกรุ่น รสชาติกลมกล่อม เหมาะสำหรับดริปและเอสเพรสโซ่',320.00,40,5,'uploads/coffee_image_1773129554266.png','2026-03-10 07:52:32'),
        (9,'ชาเขียวมัทฉะแท้จากญี่ปุ่น 100g','ผงมัทฉะเกรดพรีเมียม ชงง่ายทั้งร้อนและเย็น หอมกลิ่นชาเขียวแท้ๆ',480.00,25,5,'uploads/matcha_image_1773129570299.png','2026-03-10 07:52:32'),
        (10,'กระบอกน้ำเก็บอุณหภูมิสแตนเลส 500ml','แก้วเก็บความเย็น-ความร้อน ดีไซน์เรียบหรู จับถนัดมือ เก็บอุณหภูมิได้ยาวนาน 12 ชั่วโมง',390.00,120,6,'uploads/thermos_image_1773129583275.png','2026-03-10 07:52:32'),
        (11,'หมอนรองคอ เมมโมรี่โฟม','หมอนรองคอเพื่อสุขภาพ ช่วยรองรับสรีระต้นคอ เหมาะสำหรับพกพาเวลาเดินทาง',290.00,60,6,'uploads/neck_pillow_image_1773129597934.png','2026-03-10 07:52:32')
    ");
    echo "✔ ตารางสินค้า (Products) สร้างและเพิ่มข้อมูลสำเร็จ<br>";

    // เปิดการตรวจสอบ Foreign Key กลับมา
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    echo "<h2 style='color: green;'>ติดตั้งฐานข้อมูลเสร็จสมบูรณ์! ตอนนี้คุณสามารถลบไฟล์นี้ทิ้งได้เลยครับ</h2>";
    echo "<a href='index.php'>กลับไปยังหน้าหลัก</a>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>เกิดข้อผิดพลาด: " . $e->getMessage() . "</h3>";
}
?>
