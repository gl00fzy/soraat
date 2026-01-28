<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ร่วมงานกับเรา - AURUM INNOVATION</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #c5a059; /* สีทองหลัก */
            --hover-gold: #b08d45;    /* สีทองตอนเอาเมาส์ชี้ */
            --text-dark: #333333;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #fcfcfc; /* ขาวเกือบเทาจางๆ */
            color: var(--text-dark);
        }

        /* ปรับแต่ง Card ให้ดู Minimal */
        .card-custom {
            border: 1px solid rgba(197, 160, 89, 0.2); /* ขอบสีทองจางๆ */
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); /* เงานุ่มๆ */
            border-radius: 12px;
        }

        .company-logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-gold);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 1.1rem;
            color: var(--primary-gold);
            border-bottom: 2px solid rgba(197, 160, 89, 0.1);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* ปรับแต่ง Form Control (Input) */
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 0.25rem rgba(197, 160, 89, 0.15); /* เงาสีทอง */
        }

        /* ปรับแต่งปุ่ม */
        .btn-gold {
            background-color: var(--primary-gold);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: var(--hover-gold);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(197, 160, 89, 0.3);
        }

        .btn-outline-gold {
            border: 1px solid var(--primary-gold);
            color: var(--primary-gold);
            padding: 12px 30px;
            border-radius: 8px;
            background: transparent;
        }
        
        .btn-outline-gold:hover {
            background-color: #fff9eb;
            color: var(--hover-gold);
        }

    </style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-4">
                <div class="company-logo">AURUM INNOVATION</div>
                <div class="company-logo">สรอัฐ น้ำใส 66010914018</div>
                <p class="text-muted small">แบบฟอร์มใบสมัครงานออนไลน์</p>
            </div>

            <div class="card card-custom p-4 p-md-5">
                <form method="post" action="f.php">
                    
                    <div class="mb-5">
                        <h5 class="section-title">ข้อมูลการสมัคร</h5>
                        <div class="form-floating">
                            <select class="form-select" id="position" name="position" required>
                                <option value="" selected disabled>กรุณาเลือกตำแหน่ง</option>
                                <option value="UX/UI Designer">UX/UI Designer</option>
                                <option value="Full Stack Developer">Full Stack Developer</option>
                                <option value="Digital Marketing Manager">Digital Marketing Manager</option>
                                <option value="Data Analyst">Data Analyst</option>
                                <option value="HR Specialist">HR Specialist</option>
                            </select>
                            <label for="position">ตำแหน่งที่ต้องการสมัคร <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="section-title">ข้อมูลส่วนตัว</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="prefix" name="prefix" required>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                    <label for="prefix">คำนำหน้า</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="ชื่อ-สกุล" required>
                                    <label for="fullname">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="dob" name="dob" required>
                                    <label for="dob">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="education" name="education" required>
                                        <option value="" selected disabled>เลือกระดับการศึกษา</option>
                                        <option value="มัธยมศึกษาตอนปลาย">มัธยมศึกษาตอนปลาย / ปวช.</option>
                                        <option value="อนุปริญญา / ปวส.">อนุปริญญา / ปวส.</option>
                                        <option value="ปริญญาตรี">ปริญญาตรี</option>
                                        <option value="ปริญญาโท">ปริญญาโท</option>
                                        <option value="ปริญญาเอก">ปริญญาเอก</option>
                                    </select>
                                    <label for="education">ระดับการศึกษาสูงสุด <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="section-title">คุณสมบัติและประสบการณ์</h5>
                        
                        <div class="mb-3">
                            <label for="skills" class="form-label text-secondary small">ความสามารถพิเศษ / ทักษะ (Hard & Soft Skills)</label>
                            <textarea class="form-control" id="skills" name="skills" rows="3" placeholder="เช่น ภาษาอังกฤษ, การเขียนโปรแกรม, การตัดต่อวิดีโอ ฯลฯ" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="experience" class="form-label text-secondary small">ประสบการณ์ทำงาน (โดยสังเขป)</label>
                            <textarea class="form-control" id="experience" name="experience" rows="4" placeholder="ระบุตำแหน่ง, บริษัท, และระยะเวลาที่เคยทำงาน (หากไม่มีให้ระบุว่า 'ไม่มี')" required></textarea>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-5">
                        <button type="reset" class="btn btn-outline-gold px-5">ล้างข้อมูล</button>
                        <button type="submit" name="Submit" class="btn btn-gold px-5">ส่งใบสมัคร</button>
                    </div>

                </form>
            </div>

            <?php
            if (isset($_POST['Submit'])) {
                $position = htmlspecialchars($_POST['position']);
                $prefix = $_POST['prefix'];
                $fullname = htmlspecialchars($_POST['fullname']);
                $dob = $_POST['dob'];
                $education = $_POST['education'];
                $skills = nl2br(htmlspecialchars($_POST['skills']));
                $experience = nl2br(htmlspecialchars($_POST['experience']));

                // แปลงวันที่ให้เป็นรูปแบบไทยสวยๆ (Function จำลอง)
                $date = date_create($dob);
                $dob_format = date_format($date,"d/m/Y");
            ?>
            
            <div class="card card-custom mt-4 animate__animated animate__fadeIn border-0">
                <div class="card-header text-white text-center py-3" style="background: var(--primary-gold); border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 fw-light">ได้รับข้อมูลใบสมัครเรียบร้อยแล้ว</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3"><?php echo $prefix . $fullname; ?></h4>
                    <span class="badge rounded-pill bg-dark text-white p-2 px-3 mb-4">
                        สมัครตำแหน่ง: <?php echo $position; ?>
                    </span>
                    
                    <div class="row text-start mt-2 g-3">
                        <div class="col-md-6 border-bottom pb-2">
                            <small class="text-muted d-block">วันเกิด</small>
                            <?php echo $dob_format; ?>
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <small class="text-muted d-block">ระดับการศึกษา</small>
                            <?php echo $education; ?>
                        </div>
                        <div class="col-12 border-bottom pb-2">
                            <small class="text-muted d-block">ความสามารถพิเศษ</small>
                            <?php echo $skills; ?>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">ประสบการณ์ทำงาน</small>
                            <?php echo $experience; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>