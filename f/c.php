<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส (กอฟ)</title>
</head>

<body>

<h1> สรอัฐ น้ำใส (กอฟ) </h1>


<form method ="get" action="">
    กรอกคะแนน <input type="number" name="a"autofocus required>
    <button type="submit" name="Submit">OK</button>
</form>
<hr>

<?php

if(isset($_GET['Submit'])) {
	$score = $_GET['a'];

if ($score >= 80) {
$grade = "A" ;
} else if ($score >= 70) {
$grade = "B" ;
} else if ($score >= 60) {
$grade = "C" ;
} else if ($score >= 50) {
$grade = "D" ;
} else {
$grade = "F" ;
}
echo "<h2> คะแนน $score ได้เกรด $grade </h2>" ;	
}
?>


</body>
</html>