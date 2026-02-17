<meta charset="utf-8">
<?php

include 'connectdb.php';


$id = $_GET['id'];
$ext = $_GET['ext'];
$sql = "DELETE FROM provinces WHERE p_id = '$id'";
mysqli_query($conn, $sql) or die ("ลบข้อมูลไม่ได้");

unlink("img/$id.$ext");

echo "<script>";
echo "window.location='b.php';";
echo "</script>";
?>
