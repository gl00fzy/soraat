<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แบบฟอร์มรับข้อมูล - สรอัฐ น้ำใส (กอฟ) edit by Gemini</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f0f2f5; /* สีพื้นหลังโทนเทาอ่อนสบายตา */
        }
        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header-bg {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0); /* ไล่เฉดสี */
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .required-star {
            color: #dc3545;
            margin-left: 3px;
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card main-card mb-4">
                <div class="header-bg text-center">
                    <h2 class="mb-0 fw-bold">ฟอร์มรับข้อมูล By Gemini</h2>
                    <p class="mb-0 opacity-75">สรอัฐ น้ำใส (กอฟ)</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="post" action="" class="row g-3">
                        
                        <div class="col-md-12">
                            <label for="fullname" class="form-label fw-bold">ชื่อ-สกุล <span class="required-star">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="ระบุชื่อและนามสกุล" autofocus required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold">เบอร์โทรศัพท์ <span class="required-star">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="0xx-xxx-xxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label for="height" class="form-label fw-bold">ส่วนสูง (ซม.) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="height" name="height" min="100" max="250" required>
                                <span class="input-group-text">ซม.</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">ที่อยู่ <span class="required-star">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="ระบุที่อยู่ปัจจุบัน" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="birthday" class="form-label fw-bold">วัน/เดือน/ปีเกิด</label>
                            <input type="date" class="form-control" id="birthday" name="birthday">
                        </div>
                        <div class="col-md-6">
                            <label for="Color" class="form-label fw-bold">สีที่ชอบ</label>
                            <input type="color" class="form-control form-control-color w-100" id="Color" name="Color" value="#563d7c" title="เลือกสีที่ชอบ">
                        </div>

                        <div class="col-12">
                            <label for="major" class="form-label fw-bold">สาขาวิชา</label>
                            <select class="form-select" id="major" name="major">
                                <option value="การบัญชี">การบัญชี</option>
                                <option value="การตลาด">การตลาด</option>
                                <option value="การจัดการ">การจัดการ</option>
                                <option value="คอมพิวเตอร์ธุรกิจ" selected>คอมพิวเตอร์ธุรกิจ</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-center">
                            <button type="submit" name="Submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-send-fill"></i> สมัครสมาชิก
                            </button>
                            <button type="reset" name="Reset" class="btn btn-secondary px-4">
                                ยกเลิก
                            </button>
                        </div>

                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-center mt-2">
                            <button type="button" class="btn btn-outline-info btn-sm" onClick="window.location='https://getbootstrap.com/';">
                                Go to Bootstrap
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onMouseOver="alert('อะไรอะ');">
                                ดีค้าบอ้วน
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onClick="window.print();">
                                พิมพ์หน้าจอ
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <?php
            if (isset($_POST['Submit'])) {
                
                $fullname = htmlspecialchars($_POST['fullname']);
                $phone = htmlspecialchars($_POST['phone']);
                $height = htmlspecialchars($_POST['height']);
                $address = htmlspecialchars($_POST['address']);
                // แก้ชื่อตัวแปรรับค่าให้ตรงกับ name ใน input (birthday ตัวเล็ก)
                $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '-';
                $color = $_POST['Color'];
                $major = $_POST['major'];

                include_once("connectdb.php"); #ความหมายคือ

                $sql = "INSERT INTO register (r_id, r_name, r_phone, r_height, r_address, r_birthday, r_color, r_major) VALUES (NULL, '{$fullname}','{$phone}','{$height}','{$address}','{$birthday}','{$color}','{$major}');";
                mysqli_query($conn,$sql) or die ("insert ไม่ได้");

                echo "<script>";
                echo "alert('บันทึกข้อมูลสำเร็จ');";
                echo "</script>";
            ?>
            <?php } ?>
            </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>