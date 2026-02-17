
<?php
            $host = "localhost";
            $user = "root";
            $pwd = "Golf@2004";
            $db = "4018db";
            $conn = mysqli_connect($host,$user,$pwd,$db) or die ("เชื่อมต่อฐานข้อมูลไม่ได้ กรุณาลองใหม่");
            mysqli_query($conn,"SET NAMES utf8");
?>