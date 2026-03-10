-- Migration: เพิ่มฟังก์ชันใหม่
-- รันไฟล์นี้ 1 ครั้งเพื่ออัปเดต Database

USE shop_project;

-- 1. ตารางรูปภาพสินค้า (หลายรูปต่อ 1 สินค้า)
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 2. เพิ่มคอลัมน์ phone ใน users (ถ้ายังไม่มี)
ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email;

-- 3. เพิ่มคอลัมน์ใน orders
ALTER TABLE orders ADD COLUMN shipping_address TEXT AFTER status;
ALTER TABLE orders ADD COLUMN payment_slip VARCHAR(255) AFTER shipping_address;
ALTER TABLE orders ADD COLUMN payment_date DATETIME AFTER payment_slip;
