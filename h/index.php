<?php

session_start();

?>

<!doctype html>

<html>

<head>

<meta charset="utf-8">

<title>สรอัฐ น้ำใส</title>

</head>



<body>

<h1> เข้าสู่ระบบ - สรอัฐ </h1>

<form method="post" action="">

Username <input type="text" name="auser" autofocus required> <br>

Password <input type="password" name="apwd" required> <br>

<button type="submit" name="Submit"> LOGIN </button>

</form>



<?php

if(isset($_POST['Submit'])) {

    include_once("connectdb.php");

    $sql = "SELECT * FROM admin WHERE a_username='{$_POST['auser']}' AND a_password='{$_POST['apwd']}' LIMIT 1";

    $rs = mysqli_query($conn, $sql);

    $num = mysqli_num_rows($rs);



    if($num == 1) {

        $data = mysqli_fetch_array($rs);

        $_SESSION['aid']=$data['a_id'];

        $_SESSION['aname']=$data['a_name'];

        echo "<script>";

        echo "window.location='index2.php';"; // ส่งไปหน้าถัดไป (ถ้ามี)

        echo "</script>";

    } else {

        echo "<script>alert('Username หรือ Password ไม่ถูกต้อง');</script>";

    }



}

?>





</body>

</html>