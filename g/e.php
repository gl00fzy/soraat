<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard สรอัฐ น้ำใส</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">
    <div class="alert alert-primary text-center mb-4 shadow-sm">
        <h1>สรอัฐ น้ำใส 66010914018</h1>
        <p class="mb-0">สรุปยอดขายแยกตามประเทศ</p>
    </div>

    <?php
    include_once("connectdb.php");
    $sql = "SELECT p_country, SUM(p_amount) AS total FROM popsupermarket GROUP BY p_country";
    $rs = mysqli_query($conn, $sql);
    
    // เตรียมตัวแปร Array ไว้เก็บข้อมูล
    $labels = []; // ชื่อประเทศ
    $dataPoints = []; // ยอดเงิน
    $tableData = []; // ข้อมูลสำหรับวนลูปสร้างตาราง

    while ($data = mysqli_fetch_array($rs)) {
        $labels[] = $data['p_country'];
        $dataPoints[] = $data['total'];
        $tableData[] = $data; // เก็บข้อมูลดิบไว้ใช้ในตารางด้านล่าง
    }
    mysqli_close($conn);
    ?>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">ยอดขายรายประเทศ (Bar Chart)</div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">สัดส่วนยอดขาย (Pie Chart)</div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">ตารางข้อมูลละเอียด</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">   
                <thead class="table-light">
                    <tr>    
                        <th>ประเทศ</th>
                        <th class="text-end">จำนวนเงินรวม</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tableData as $row) { ?>
                <tr>
                    <td><?php echo $row['p_country'];?></td>
                    <td align="right"><?php echo number_format($row['total'],0);?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// รับข้อมูลจาก PHP มาเป็น JSON
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($dataPoints); ?>;

// ตั้งค่าสีสวยๆ ให้กราฟ
const bgColors = [
    'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 
    'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 
    'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
];

// สร้าง Bar Chart
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'ยอดขายรวม',
            data: data,
            backgroundColor: bgColors,
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});

// สร้าง Pie Chart
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: bgColors
        }]
    }
});
</script>

</body>
</html>