<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>สรอัฐ น้ำใส</title>
</head>

<body>
<h1> สวัสดี งาน i -- สรอัฐ น้ำใส (กอฟ) </h1>

<?php
include 'connectdb.php';

$sql = "SELECT * FROM regions";
$rs = mysqli_query($conn, $sql);


while ($data = mysqli_fetch_array($rs)) {
    echo $data['r_id']. "<br>";
    echo $data['r_name']. "<br>";
}

mysqli_close($conn);
?>

<table border="1">
    <tr>
        <th>Region ID</th>
        <th>Region Name</th>
    </tr>
    <?php
    while ($data = mysqli_fetch_array($rs)) {
        echo "<tr>";
        echo "<td>" . $data['r_id'] . "</td>";
        echo "<td>" . $data['r_name'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
