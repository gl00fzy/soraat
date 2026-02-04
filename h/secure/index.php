<?php
session_start();
include_once("connectdb.php");

// ตรวจสอบเมื่อมีการกดปุ่ม Login
if (isset($_POST['Submit'])) {
    $username = $_POST['auser'];
    $password_input = $_POST['apwd']; // รหัสที่กรอกเข้ามา (เช่น 1234)

    // 1. ดึงข้อมูลจาก DB โดยค้นหาแค่ Username อย่างเดียวมาก่อน
    // (ยังไม่เช็ค Password ใน SQL)
    $sql = "SELECT a_id, a_name, a_password FROM admin WHERE a_username = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // ถ้าเจอ Username นี้ในระบบ
        if ($row = mysqli_fetch_assoc($result)) {
            
            // 2. ใช้ password_verify ตรวจสอบรหัสผ่าน
            // มันจะเอารหัสที่กรอก (1234) ไปเทียบกับ Hash ยาวๆ ในตัวแปร $row['a_password']
            if (password_verify($password_input, $row['a_password'])) { 
                
                // --- LOGIN สำเร็จ ---
                $_SESSION['aid'] = $row['a_id'];
                $_SESSION['aname'] = $row['a_name'];

                echo "<script>
                    alert('ยินดีต้อนรับ คุณ {$row['a_name']}');
                    window.location = 'index2.php'; // เปลี่ยนเป็นหน้าปลายทางของคุณ
                </script>";
                exit;

            } else {
                // รหัสผ่านผิด
                $error_msg = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            // ไม่พบ Username
            $error_msg = "ไม่พบชื่อผู้ใช้งานนี้ในระบบ";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = "เกิดข้อผิดพลาดของระบบ (SQL Error)";
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ - สรอัฐ น้ำใส</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(to right, #ec008c, #fc6767);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .btn-custom {
            background: linear-gradient(to right, #ec008c, #fc6767);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(236, 0, 140, 0.4);
            color: white;
        }
        .form-control:focus {
            border-color: #ec008c;
            box-shadow: 0 0 0 0.25rem rgba(236, 0, 140, 0.25);
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="login-header">
                    <h3 class="mb-0 fw-bold">สรอัฐ น้ำใส</h3>
                    <small>ระบบจัดการข้อมูล (Admin)</small>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <h4 class="text-center text-muted mb-4">เข้าสู่ระบบ</h4>

                    <?php if(isset($error_msg)) { ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?php echo $error_msg; ?>
                        </div>
                    <?php } ?>

                    <form method="post" action="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="auser" id="floatingInput" placeholder="Username" required autofocus>
                            <label for="floatingInput">ชื่อผู้ใช้งาน</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" name="apwd" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">รหัสผ่าน</label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="Submit" class="btn btn-custom btn-lg">
                                LOGIN
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light border-0">
                    <small class="text-muted">&copy; 2024 Soraat Namsai System</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>