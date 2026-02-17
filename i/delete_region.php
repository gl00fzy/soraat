<meta charset="utf-8">
<?php
include 'connectdb.php';
if (isset($_POST['Submit'])) {
    $rname = $_POST['rname'];
    $sql2 = "INSERT INTO regions (r_id, r_name) VALUES (NULL, '$rname')";
    mysqli_query($conn, $sql2) or die ("เพิ่มข้อมูลไม่ได้");
}
?>