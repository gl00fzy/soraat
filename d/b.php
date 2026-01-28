<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส (กอฟ)</title>
</head>

<body>
<h1>ฟอร์มรับข้อมูล - สรอัฐ น้ำใส (กอฟ) </h1>

<form method="post" action="">
ชื่อ-สกุล <br><input type="text" name="fullname" autofocus required> <font color="#FF0000">*</font><br>
เบอร์โทรศัพท์ <br> <input type="text" name="phone" required> <font color="#FF0000">*</font><br>
ส่วนสูง <br> <input type="number" name="height" min="100" max="200" required> ซม.<br>


ที่อยู่ <br> <textarea name="address" cols="40" rows="4" required></textarea> <br>
วัน/เดือน/ปีเกิด <br> <input type="date" name="Birthday"> <br>
สีที่ชอบ<input type="color" name="Color"> <br> 

สาขาวิชา
<select name="major">
	<option value="การบัญชี">การบัญชี</option>
    <option value="การตลาด">การตลาด</option>
    <option value="การจัดการ">การจัดการ</option>
    <option value="คอมพิวเตอร์ธุรกิจ">คอมพิวเตอร์ธุรกิจ</option>
</select> <br>  

<!--<input type="submit" name="Submit" value="สมัครสมาชิก">-->
<br> <button type="submit" name="Submit" >สมัครสมาชิก</button> <br> <br>
<button type="reset" name="Reset">ยกเลิก</button>
<button type="button" onClick="window.location='https://getbootstrap.com/';">Go to Bootstrap</button>
<button type="button" onMouseOver="alert('อะไรอะ');">ดีค้าบอ้วน</button>
<button type="button" onClick="window.print();">พิมพ์หน้าจอ</button>

</form> 
<hr>

<?php
if (isset($_POST['Submit'])) {
	
	$fullname = $_POST['fullname'];
	$phone = $_POST['phone'];
	$height = $_POST['height'];
	$address = $_POST['address'];
	$birthday = $_POST['birthday'];
	$color = $_POST['Color'];
	$major = $_POST['major'];
	
	
	
	echo "ชื่อ-สกุล :".$fullname."<br>";	
	echo "เบอร์โทรศัพท์ :".$phone."<br>";
	echo "ส่วนสูง :".$height."<br>";
	echo "ที่อยู่ :".$address."<br>";
	echo "วัน/เดือน/ปีเกิด :".$birthday."<br>";
	echo "สีที่ชอบ :<div style='background-color:{$color}; width:300px'>".$color."</div>";
	echo "สาขาวิชา :".$major."<br>";



}

?>






</body>
</html>
