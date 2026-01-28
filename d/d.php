<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ฟอร์มรับข้อมูล - สรอัฐ น้ำใส (กอฟ)</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, #eef2f7, #fafbfc);
        }

        .main-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .header-bg {
            background: linear-gradient(135deg, #123b7e, #0d6efd);
            color: #fff;
            padding: 32px 20px;
            border-radius: 20px 20px 0 0;
            box-shadow: inset 0 -5px 12px rgba(255,255,255,0.2);
        }

        .header-bg h2 {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* ปรับ input ให้หรู */
        .form-control, .form-select, textarea {
            border-radius: 12px !important;
            border: 1px solid #dcdfe6;
            padding: 10px 14px;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
        }

        .required-star {
            color: #e63946;
        }

        /* ปุ่มหรู */
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #4da3ff);
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            transition: 0.25s;
            font-weight: 500;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13,110,253,0.25);
        }

        .btn-secondary, .btn-outline-info, .btn-outline-warning, .btn-outline-success {
            border-radius: 10px;
            padding: 8px 20px;
        }

        /* การ์ดแสดงผล */
        .result-card {
            border-radius: 18px;
            overflow: hidden;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        }
        .result-card .card-header {
            background: linear-gradient(135deg, #28a745, #5cd67f);
        }

        /* Badge สี */
        .color-badge {
            padding: 10px 22px;
            border-radius: 30px;
            display: inline-block;
            color: white;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card main-card mb-4">
                <div class="header-bg text-center">
                    <h2 class="mb-1">แบบฟอร์มรับข้อมูล By ChatGPT</h2>
                    <p class="mb-0 opacity-75">สรอัฐ น้ำใส (กอฟ)</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="post" action="" class="row g-3">
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">ชื่อ-สกุล <span class="required-star">*</span></label>
                            <input type="text" class="form-control" name="fullname" placeholder="ระบุชื่อและนามสกุล" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">เบอร์โทรศัพท์ <span class="required-star">*</span></label>
                            <input type="tel" class="form-control" name="phone" placeholder="0xx-xxx-xxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ส่วนสูง (ซม.) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="height" required>
                                <span class="input-group-text">ซม.</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">ที่อยู่ <span class="required-star">*</span></label>
                            <textarea class="form-control" name="address" rows="3" placeholder="ระบุที่อยู่ปัจจุบัน" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">วัน/เดือน/ปีเกิด</label>
                            <input type="date" class="form-control" name="birthday">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">สีที่ชอบ</label>
                            <input type="color" class="form-control form-control-color w-100" name="Color" value="#563d7c">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">สาขาวิชา</label>
                            <select class="form-select" name="major">
                                <option value="การบัญชี">การบัญชี</option>
                                <option value="การตลาด">การตลาด</option>
                                <option value="การจัดการ">การจัดการ</option>
                                <option value="คอมพิวเตอร์ธุรกิจ" selected>คอมพิวเตอร์ธุรกิจ</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-center">
                            <button type="submit" name="Submit" class="btn btn-primary shadow-sm">
                                สมัครสมาชิก
                            </button>
                            <button type="reset" class="btn btn-secondary px-4">ยกเลิก</button>
                        </div>

                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-center mt-2">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="window.location='https://getbootstrap.com/';">
                                Go to Bootstrap
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onmouseover="alert('อะไรอะ');">
                                ดีค้าบอ้วน
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="window.print();">
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
                $birthday = $_POST['birthday'] ?: '-';
                $color = $_POST['Color'];
                $major = $_POST['major'];
            ?>

            <div class="card result-card mt-4">
                <div class="card-header text-white">
                    <h5 class="mb-0">บันทึกข้อมูลสำเร็จ</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-2">
                        <div class="col-md-6"><strong>ชื่อ-สกุล :</strong> <?= $fullname ?></div>
                        <div class="col-md-6"><strong>เบอร์โทรศัพท์ :</strong> <?= $phone ?></div>
                        <div class="col-md-6"><strong>ส่วนสูง :</strong> <?= $height ?> ซม.</div>
                        <div class="col-md-6"><strong>วันเกิด :</strong> <?= $birthday ?></div>
                        <div class="col-md-6"><strong>สาขาวิชา :</strong> <?= $major ?></div>
                        <div class="col-md-12"><strong>ที่อยู่ :</strong> <?= $address ?></div>

                        <div class="col-md-12 mt-2">
                            <strong>สีที่ชอบ :</strong> 
                            <span class="color-badge" style="background-color:<?= $color ?>;">
                                <?= $color ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>

        </div>
    </div>
</div>

</body>
</html>
