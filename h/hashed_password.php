<?php
// บังคับให้แสดง Error ทุกอย่าง (ช่วยให้รู้ว่าผิดตรงไหน)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// กำหนดรหัสผ่านที่ต้องการ
$password_plaintext = '1234'; 

// สร้าง Hash
$hash = password_hash($password_plaintext, PASSWORD_DEFAULT);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>สร้างรหัสผ่าน Hash</title>
</head>
<body style="font-family: sans-serif; padding: 40px; background-color: #f4f4f4;">

    <div style="background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">ตัวสร้างรหัสผ่าน Hash</h2>
        
        <p>รหัสผ่านต้นฉบับ: <strong><?php echo $password_plaintext; ?></strong></p>
        
        <label>ค่า Hash ที่ต้องก๊อปไปใส่ใน Database (ช่อง a_password):</label><br>
        <input type="text" value="<?php echo $hash; ?>" onclick="this.select()" style="width: 100%; padding: 10px; margin-top: 5px; font-size: 14px; background: #eee; border: 1px solid #ccc;">
        
        <p style="color: red; font-size: 0.9em; margin-top: 20px;">
            *หมายเหตุ: ใน Database (phpMyAdmin) ช่อง <code>a_password</code> ต้องตั้ง Type เป็น <code>VARCHAR</code> และ Length อย่างน้อย <code>100</code> นะครับ ไม่งั้นรหัสจะถูกตัด
        </p>
    </div>

</body>
</html>