<?php
// ------------------------------------------------------------------
// ส่วนฟังก์ชัน (ประกาศไว้บนสุด เพื่อแก้ปัญหาแจ้งเตือน Error)
// ------------------------------------------------------------------

function thai_date($date) {
    // 1. ถ้าไม่มีค่าวันที่ส่งมา ให้คืนค่าเป็นขีด
    if (empty($date) || $date == '0000-00-00') {
        return "-";
    }

 	$months = array(
    1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 
    5=>"พฤษภาคม", 6=>"มิถุนายน", 7=>"กรกฎาคม", 8=>"สิงหาคม", 
    9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤศจิกายน", 12=>"ธันวาคม"
	);

    $time = strtotime($date);

    // 2. ถ้าแปลงวันที่ไม่สำเร็จ ให้คืนค่าเป็นขีด
    if (!$time) {
        return "-";
    }

    $d = date("j", $time);
    $m = $months[(int)date("n", $time)]; // ใส่ (int) เพื่อยืนยันว่าเป็นตัวเลข
    $y = date("Y", $time) + 543;

    return "$d $m $y";
}

// ------------------------------------------------------------------
// ส่วนรับค่าข้อมูล (Logic)
// ------------------------------------------------------------------

// ตัวแปรสำหรับเก็บค่าเริ่มต้น (เพื่อไม่ให้ HTML Error ถ้ารันไฟล์เปล่าๆ)
$show_content = false;
$position = $prefix = $fullname = $dob_thai = $education = $skills = $experience = "-";
$age = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $show_content = true;
    
    // รับค่าและใส่ ?? '' เพื่อกัน Error กรณีค่าว่าง
	$position = htmlspecialchars($_POST['position'] ?? '-');
	$prefix = $_POST['prefix'] ?? "";
	$fullname = htmlspecialchars($_POST['fullname'] ?? '');
	$dob = $_POST['dob'] ?? "";
	$education = $_POST['education'] ?? '-';
	$skills = nl2br(htmlspecialchars($_POST['skills'] ?? '-'));
	$experience = nl2br(htmlspecialchars($_POST['experience'] ?? '-'));

    // เรียกใช้ฟังก์ชันวันที่
    $dob_thai = thai_date($dob);

    // คำนวณอายุ
    if (!empty($dob)) {
        $birthDate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthDate->diff($today)->y;
    }

} else {
    // กรณีเข้าไฟล์นี้มาตรงๆ โดยไม่ผ่านฟอร์ม
    // จะปล่อยผ่านไปแสดงหน้า Error ใน HTML ด้านล่าง
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สรุปข้อมูลผู้สมัคร - AURUM INNOVATION</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #c5a059;
            --hover-gold: #b08d45;
            --text-dark: #333333;
            --bg-light: #fcfcfc;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .card-resume {
            border: none;
            background: #ffffff;
            box-shadow: 0 15px 40px rgba(197, 160, 89, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .resume-header-strip {
            height: 8px;
            background: linear-gradient(90deg, var(--primary-gold), #e6c88b);
            width: 100%;
        }

        .company-brand {
            font-weight: 700;
            color: var(--primary-gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 1.2rem;
        }

        .applicant-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .position-badge {
            background-color: var(--primary-gold);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .section-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-gold);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .data-label {
            font-weight: 500;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .data-value {
            font-size: 1.05rem;
            color: #333;
            font-weight: 400;
        }

        .action-bar {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .btn-gold {
            background-color: var(--primary-gold);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .btn-gold:hover {
            background-color: var(--hover-gold);
            color: white;
            box-shadow: 0 5px 15px rgba(197, 160, 89, 0.3);
        }
        
        .btn-outline-secondary {
            border: 1px solid #ccc;
            color: #666;
        }

        @media print {
            body { background-color: white; }
            .card-resume { box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <?php if ($show_content): ?>
                <div class="card card-resume animate__animated animate__fadeInUp">
                    <div class="resume-header-strip"></div>
                    
                    <div class="card-body p-5">
                        
                        <div class="row align-items-center mb-4">
                            <div class="col-md-8">
                                <div class="company-brand mb-2">AURUM INNOVATION</div>
                                <h1 class="applicant-name"><?php echo $prefix . $fullname; ?></h1>
                                <div class="text-muted mt-1">อายุ <?php echo $age; ?> ปี</div>
                            </div>
                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <span class="position-badge shadow-sm">
                                    <?php echo $position; ?>
                                </span>
                            </div>
                        </div>

                        <div class="section-header">ข้อมูลส่วนตัว / Personal Information</div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="data-label">วันเดือนปีเกิด</div>
                                <div class="data-value"><?php echo $dob_thai; ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="data-label">ระดับการศึกษาสูงสุด</div>
                                <div class="data-value"><?php echo $education; ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="data-label">วันที่ยื่นใบสมัคร</div>
                                <div class="data-value"><?php echo thai_date(date("Y-m-d")); ?></div>
                            </div>
                        </div>

                        <div class="section-header">ทักษะและความสามารถ / Skills</div>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-3 bg-light rounded border border-light">
                                    <?php echo $skills; ?>
                                </div>
                            </div>
                        </div>

                        <div class="section-header">ประสบการณ์ทำงาน / Work Experience</div>
                        <div class="row">
                            <div class="col-12">
                                <div class="data-value" style="line-height: 1.8;">
                                    <?php echo $experience; ?>
                                </div>
                            </div>
                        </div>

                        <div class="action-bar d-flex justify-content-between no-print">
                            <button onclick="window.history.back()" class="btn btn-outline-secondary px-4">
                                 แก้ไขข้อมูล
                            </button>
                            <div>
                                <button onclick="window.print()" class="btn btn-outline-dark me-2">
                                    พิมพ์ใบสมัคร
                                </button>
                                <button onclick="alert('บันทึกข้อมูลลงฐานข้อมูลเรียบร้อย (จำลอง)')" class="btn btn-gold px-4">
                                    ยืนยันการสมัคร
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            <?php else: ?>
                <div class="text-center mt-5">
                    <div class="alert alert-warning d-inline-block px-5">
                        กรุณากรอกข้อมูลผ่านแบบฟอร์มใบสมัครก่อน
                    </div>
                    <br>
                    <a href="index.php" class="btn btn-gold mt-3">กลับไปหน้าแบบฟอร์ม</a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>