<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส (กอฟ)</title>
</head>

<body>

<h1> สรอัฐ น้ำใส (กอฟ) - โปรแกรมสูตรคูณ </h1>

<form method ="get" action="">
    กรอกสูตรคูณที่อยากได้ <input type="number" name="a"autofocus required>
    <button type="submit" name="Submit">OK</button>
</form>
<hr>

<?php
if(isset($_GET['Submit'])) {
	$a = $_GET['a'];


for($b=1; $b<=12; $b++) {
$sum = $a * $b ;
echo "<h1>$a x $b = $sum </h1>" ;
}
}
?>


</body>
</html>