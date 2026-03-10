<?php
require_once dirname(__DIR__) . '/includes/db.php';

echo "<h1>Starting Product & Category Installation...</h1>";

try {
    // ปิดการตรวจสอบ Foreign Key ชั่วคราวเพื่อป้องกัน Error ตอนลบตาราง
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    // สร้างตาราง Categories (ถ้ายังไม่มี)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `categories` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
    ");

    echo "✔ ตรวจสอบโครงสร้างตารางสำเร็จ<br>";

    // ชุดข้อมูลหมวดหมู่และสินค้าที่จะเพิ่ม
    $new_categories = [
        'Test Category', 
        'เสื้อผ้าแฟชั่น', 
        'อุปกรณ์ไอที', 
        'ความงามและสุขภาพ', 
        'อาหารและเครื่องดื่ม', 
        'เครื่องใช้ในบ้าน'
    ];

    $new_products = [
        ['เสื้อยืด Cotton 100% ลายมินิมอล', 'เสื้อยืดเนื้อผ้านุ่ม สวมใส่สบาย ระบายอากาศได้ดีเยี่ยม เหมาะสำหรับใส่ในทุกโอกาส', 250.00, 100, 'เสื้อผ้าแฟชั่น', 'uploads/tshirt_image_1773129457269.png'],
        ['กางเกงยีนส์ทรงขากระบอก สไตล์เกาหลี', 'กางเกงยีนส์แฟชั่นยอดฮิต เข้าทรงสวย ผ้าทนทาน สวมใส่สบายไม่อึดอัด', 590.00, 50, 'เสื้อผ้าแฟชั่น', 'uploads/jeans_image_1773129469944.png'],
        ['หูฟังไร้สาย Bluetooth 5.3', 'หูฟัง TWS เสียงใส เบสหนัก แบตเตอรี่อึดใช้งานต่อเนื่อง 24 ชั่วโมง มีระบบตัดเสียงรบกวน', 1290.00, 30, 'อุปกรณ์ไอที', 'uploads/earbuds_image_1773129483169.png'],
        ['พาวเวอร์แบงค์ 20000mAh ชาร์จเร็ว', 'แบตสำรองความจุสูง รองรับ PD Fast Charge น้ำหนักเบา พกพาพกขึ้นเครื่องได้', 850.00, 45, 'อุปกรณ์ไอที', 'uploads/powerbank_image_1773129496151.png'],
        ['เซรั่มบำรุงผิวหน้าใส 30ml', 'เซรั่มวิตามินซีเข้มข้น ช่วยกู้ผิวโทรมให้กลับมากระจ่างใส ลดรอยสิวและจุดด่างดำ', 690.00, 80, 'ความงามและสุขภาพ', 'uploads/serum_image_1773129509623.png'],
        ['ครีมกันแดด SPF50+ PA++++', 'ครีมกันแดดเนื้อบางเบา ซึมไว ไม่เหนอะหนะ ปกป้องผิวจาก UVA และ UVB เต็มประสิทธิภาพ', 450.00, 70, 'ความงามและสุขภาพ', 'uploads/sunscreen_image_1773129538454.png'],
        ['เมล็ดกาแฟอาราบิก้าคั่วกลาง 250g', 'เมล็ดกาแฟคุณภาพพรีเมียม หอมกรุ่น รสชาติกลมกล่อม เหมาะสำหรับดริปและเอสเพรสโซ่', 320.00, 40, 'อาหารและเครื่องดื่ม', 'uploads/coffee_image_1773129554266.png'],
        ['ชาเขียวมัทฉะแท้จากญี่ปุ่น 100g', 'ผงมัทฉะเกรดพรีเมียม ชงง่ายทั้งร้อนและเย็น หอมกลิ่นชาเขียวแท้ๆ', 480.00, 25, 'อาหารและเครื่องดื่ม', 'uploads/matcha_image_1773129570299.png'],
        ['กระบอกน้ำเก็บอุณหภูมิสแตนเลส 500ml', 'แก้วเก็บความเย็น-ความร้อน ดีไซน์เรียบหรู จับถนัดมือ เก็บอุณหภูมิได้ยาวนาน 12 ชั่วโมง', 390.00, 120, 'เครื่องใช้ในบ้าน', 'uploads/thermos_image_1773129583275.png'],
        ['หมอนรองคอ เมมโมรี่โฟม', 'หมอนรองคอเพื่อสุขภาพ ช่วยรองรับสรีระต้นคอ เหมาะสำหรับพกพาเวลาเดินทาง', 290.00, 60, 'เครื่องใช้ในบ้าน', 'uploads/neck_pillow_image_1773129597934.png']
    ];

    $category_map = [];

    // เพิ่มหมวดหมู่ (ถ้ายังไม่มีชื่อนี้) 
    $stmtCheckCat = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmtInsertCat = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");

    foreach ($new_categories as $cat_name) {
        $stmtCheckCat->execute([$cat_name]);
        $existing = $stmtCheckCat->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            $category_map[$cat_name] = $existing['id'];
        } else {
            $stmtInsertCat->execute([$cat_name]);
            $category_map[$cat_name] = $pdo->lastInsertId();
        }
    }
    echo "✔ ตรวจสอบและจัดการหมวดหมู่ (Categories) สำเร็จ<br>";

    // เพิ่มสินค้า (ละเว้น ID เพื่อให้รันต่อจากของเดิมอัตโนมัติ)
    $stmtCheckProd = $pdo->prepare("SELECT id FROM products WHERE name = ?");
    $stmtInsertProd = $pdo->prepare("INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
    
    $added_count = 0;
    foreach ($new_products as $p) {
        $stmtCheckProd->execute([$p[0]]);
        if (!$stmtCheckProd->fetch()) {
            $cat_id = $category_map[$p[4]]; // ดึง ID หมวดหมู่จาก Map
            $stmtInsertProd->execute([$p[0], $p[1], $p[2], $p[3], $cat_id, $p[5]]);
            $added_count++;
        }
    }
    
    echo "✔ ตารางสินค้า (Products) อัปเดตข้อมูลสำเร็จ (เพิ่มสินค้าใหม่ $added_count รายการ)<br>";

    // เปิดการตรวจสอบ Foreign Key กลับมา
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    echo "<h2 style='color: green;'>ติดตั้งฐานข้อมูลเสร็จสมบูรณ์! ตอนนี้คุณสามารถลบไฟล์นี้ทิ้งได้เลยครับ</h2>";
    echo "<a href='index.php'>กลับไปยังหน้าหลัก</a>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>เกิดข้อผิดพลาด: " . $e->getMessage() . "</h3>";
}
?>
