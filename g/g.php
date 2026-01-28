<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard สรุปยอดขายรายเดือน</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; font-family: 'Sarabun', sans-serif; }
    .card { border: none; border-radius: 12px; }
    
    /* เพิ่ม CSS เพื่อกำหนดความสูงของกราฟแท่งให้เหมาะสม */
    .chart-container {
        position: relative;
        height: 400px; /* กำหนดความสูงไว้ที่ 400px (ปรับแก้ได้) */
        width: 100%;
    }
</style>
</head>

<body>

<div class="container-fluid px-4 py-4">
    
    <div class="alert alert-primary text-center shadow-sm mb-4">
        <h2 class="mb-0">สรอัฐ น้ำใส (66010914018)</h2>
        <p class="mb-0">Dashboard สรุปยอดขายรายเดือน (Full Screen)</p>
    </div>

    <?php
    include_once("connectdb.php");
    $sql = "SELECT MONTH(p_date) AS Month, SUM(p_amount) AS Total_Sales 
            FROM popsupermarket GROUP BY MONTH(p_date) ORDER BY Month";
    $rs = mysqli_query($conn, $sql);

    $labels = []; 
    $dataPoints = []; 
    $tableData = []; 
    
    $thai_months = [
        1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 5=>"พฤษภาคม", 6=>"มิถุนายน", 
        7=>"กรกฎาคม", 8=>"สิงหาคม", 9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤศจิกายน", 12=>"ธันวาคม"
    ];

    while ($data = mysqli_fetch_array($rs)) {
        $monthName = $thai_months[$data['Month']]; 
        $labels[] = $monthName; 
        $dataPoints[] = $data['Total_Sales']; 
        $data['MonthName'] = $monthName;
        $tableData[] = $data; 
    }
    mysqli_close($conn);
    ?>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">แนวโน้มยอดขายรายเดือน</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">สัดส่วนยอดขาย (Pie Chart)</div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">สัดส่วนยอดขาย (Doughnut Chart)</div>
                <div class="card-body">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">ตารางข้อมูลละเอียด</div>
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">   
                <thead class="table-light">
                    <tr>    
                        <th>เดือน</th>
                        <th>ยอดขายรวม (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $grandTotal = 0;
                foreach ($tableData as $row) { 
                    $grandTotal += $row['Total_Sales'];
                ?>
                <tr>
                    <td><?php echo $row['MonthName'];?></td>
                    <td align="right"><?php echo number_format($row['Total_Sales'],0);?></td>
                </tr>
                <?php } ?>
                <tr class="table-secondary fw-bold">
                    <td>รวมทั้งสิ้น</td>
                    <td align="right"><?php echo number_format($grandTotal, 0);?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($dataPoints); ?>;
const colors = [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
    '#E7E9ED', '#76A346', '#004B8D', '#D43F3A', '#46BFBD', '#FDB45C'
];

// 1. Bar Chart Config (ปรับแต่งให้ยืดหยุ่น)
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'ยอดขาย (บาท)',
            data: data,
            backgroundColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // สำคัญ! ปิดตรงนี้เพื่อให้กราฟยืดเต็มกล่อง div ได้
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Config สำหรับ Pie/Doughnut
const pieConfig = {
    labels: labels,
    datasets: [{
        data: data,
        backgroundColor: colors
    }]
};

// 2. Pie Chart
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: pieConfig
});

// 3. Doughnut Chart
new Chart(document.getElementById('doughnutChart'), {
    type: 'doughnut',
    data: pieConfig
});
</script>

</body>
</html>