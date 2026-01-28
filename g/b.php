<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส (กอฟ) </title>
</head>

<body>

<h1> สรอัฐ น้ำใส 66010914018 รายงานตัวแล้วครับจารย์ </h1>

<form method ="post" action="">
    คำค้น <input type="string" name="a"autofocus required>
    <button type="submit" name="Submit">OK</button>
</form>
<br>

<table border="1">   
<tr>    
    <th>Order_ID</th>
    <th>ชื่อสินค้า</th>
    <th>ประเภทสินค้า</th>
    <th>วันที่</th>
    <th>ประเทศ</th>
    <th>จำนวนเงิน</th>
    <th>รูปภาพ</th>
</tr>

<?php
include_once("connectdb.php");
@$kw = $_POST['a'];
$sql ="SELECT * FROM `popsupermarket` WHERE p_product_name like '%{$kw}%' or p_country like '%{$kw}%' or p_category like '%{$kw}%'";
$rs = mysqli_query($conn, $sql);
$total = 0;
while ($data = mysqli_fetch_array($rs)) {
    $total += $data['p_amount'];
?>
<tr>
    <td><?php echo $data['p_order_id'];?></td>
    <td><?php echo $data['p_product_name'];?></td>
    <td><?php echo $data['p_category'];?></td>
    <td><?php echo $data['p_date'];?></td>
    <td><?php echo $data['p_country'];?></td>
    <td align="right"><?php echo number_format($data['p_amount'],0);?></td>
    <td> <img src="images/<?php echo $data['p_product_name'];?>.jpg" width="55"></td>
</tr>
<?php } ?>

<tr>
    <td colspan="5" align="right"><strong>รวมยอดทั้งหมด</strong></td>
    <td align="right"><strong><?php echo number_format($total,0);?></strong></td>
    <td></td>
</tr>

<?php
mysqli_close($conn);
?>



</table>


</body>
</html>
