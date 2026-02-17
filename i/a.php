<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส</title>
</head>

<body>
<h1> สวัสดี งาน i -- สรอัฐ น้ำใส (กอฟ) </h1>

<form method="post">
    ชื่อภาค <input type="text" name="rname" autofocus required>
    <button type="submit" name="Submit">บันทึก</button>
</form> <br><br>

<?php
include 'connectdb.php';
if (isset($_POST['Submit'])) {
    $rname = $_POST['rname'];
    $sql2 = "INSERT INTO regions (r_id, r_name) VALUES (NULL, '$rname')";
    mysqli_query($conn, $sql2) or die ("เพิ่มข้อมูลไม่ได้");
}
?>


<table border="1">
    <tr>
        <th>Region ID</th>
        <th>Region Name</th>
    </tr>

<?php

$sql = "SELECT * FROM regions";
$rs = mysqli_query($conn, $sql);
while ($data = mysqli_fetch_array($rs)) {
?>
    <tr>
    <td><?php echo $data['r_id'] ?></td>
    <td><?php echo $data['r_name'] ?></td>
    <td><img src="img/delete.png" width="20" height="20"></td>
    </tr>
<?php
}
?>

<?php
mysqli_close($conn);
?>

</table>

</body>
</html>
