<?php

require_once '../dbcon/conn.php';

if (!isset($_SESSION['USERID'])) {

  redirect(web_root . "admin/index.php");

}

?>

<?php

$query = "SELECT * FROM tblorder";

$mydb->setQuery($query);

$cur = $mydb->executeQuery();

$rowscount = $mydb->num_rows($cur);

$res = isset($rowscount) ? $rowscount : 0;



if ($res > 0) {

  $order = '<span style="color:black;">' . $res . '</span>';

} else {

  $order = '<span> ' . $res . '</span>';

}

?>

<?php

$query = "SELECT * FROM tblcustomer";

$mydb->setQuery($query);

$cur = $mydb->executeQuery();

$rowscount = $mydb->num_rows($cur);

$res = isset($rowscount) ? $rowscount : 0;



if ($res > 0) {

  $customer = '<span style="color:black;">' . $res . '</span>';

} else {

  $customer = '<span> ' . $res . '</span>';

}

?>

<?php

$query = "SELECT * FROM stocks";

$mydb->setQuery($query);

$cur = $mydb->executeQuery();

$rowscount = $mydb->num_rows($cur);

$res = isset($rowscount) ? $rowscount : 0;



if ($res > 0) {

  $product = '<span style="color:black;">' . $res . '</span>';

} else {

  $product = '<span> ' . $res . '</span>';

}

?>

<?php

$query = "SELECT * FROM tblsummary WHERE ORDEREDSTATS = 'Delivered Successfully'";

$mydb->setQuery($query);

$cur = $mydb->executeQuery();

$rowscount = $mydb->num_rows($cur);

$res = isset($rowscount) ? $rowscount : 0;



if ($res > 0) {

  $receive = '<span style="color:black;">' . $res . '</span>';

} else {

  $receive = '<span> ' . $res . '</span>';

}

?>


<?php

$user = new User();

$singleuser = $user->single_user($_SESSION['USERID']);



?>

<?php
function getTotalSales() {
    global $mydb;
    
    $query1 = "SELECT SUM(totalPrice) as totalSales FROM orderpos";
    $mydb->setQuery($query1);
    $result1 = $mydb->loadSingleResult();
    $totalSalesOrderpos = isset($result1->totalSales) ? $result1->totalSales : 0;
    
    $query2 = "SELECT SUM(PAYMENT) as totalPayments FROM tblsummary WHERE ORDEREDSTATS = 'Delivered Successfully'";
    $mydb->setQuery($query2);
    $result2 = $mydb->loadSingleResult();
    $totalPaymentsTblsummary = isset($result2->totalPayments) ? $result2->totalPayments : 0;
    
    $totalSales = $totalSalesOrderpos + $totalPaymentsTblsummary;
    
    return $totalSales;
}
$totalSales = getTotalSales();
?>


<?php
$sql = "SELECT SUM(totalPrice) AS totalRevenue FROM orderpos";
$result = $conn->query($sql);

// Check if we have a result
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $totalRevenue = $row['totalRevenue'];
} else {
    $totalRevenue = 0; 
}
?>

<?php
function getGCashTotal($conn) {
    $sql = "SELECT SUM(PAYMENT) as totalGCash FROM tblsummary WHERE PAYMENTMETHOD = 'GCash'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['totalGCash'];
    } else {
        return 0;
    }
}

$gcashTotal = getGCashTotal($conn);
?>

<?php 
$sql = "SELECT SUM(ORDEREDPRICE) AS totalRevenueOnline FROM tblorder";
$result = $conn->query($sql);

// Check if we have a result
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $totalRevenueOnline = $row['totalRevenueOnline'];
} else {
    $totalRevenueOnline = 0; 
}

function getOrderStatusCounts($conn) {
    $sql = "SELECT 
                SUM(CASE WHEN ORDEREDSTATS = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN ORDEREDSTATS = 'Preparing for Delivery' THEN 1 ELSE 0 END) as preparing_count,
                SUM(CASE WHEN ORDEREDSTATS = 'Approaching Destination' THEN 1 ELSE 0 END) as approaching_count
            FROM tblsummary";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        return ['pending_count' => 0, 'preparing_count' => 0, 'approaching_count' => 0];
    }
}

$statusCounts = getOrderStatusCounts($conn);
mysqli_close($conn);
?>
<style>


.border-bottom-red {
    border-bottom: 3px solid #fd2323;
}

</style>
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Overall Total Sales of In-store and Online Purchases</h4>
                <div class="stats-figure"> &#8369;<?php echo number_format($totalSales, 2); ?></div>
                <div class="stats-meta text-success">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                    </svg> 
                </div>
            </div>
            <!--//app-card-body-->
            <a class="app-card-link-mask" href="#"></a>
        </div>
        <!--//app-card-->
    </div>
    <!--//col-->
    <!-- <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Number of Customers</h4>
                <div class="stats-figure"><?php echo $customer; ?></div>
            </div>

            <a class="app-card-link-mask" href="<?php echo web_root; ?>admin/customers/index.php"></a>
        </div>
    </div> -->

    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"> <i class="fas fa-boxes mr-2" style="color: #4e73df;">></i> Total Stock Items</h4>
                <div class="stats-figure"><?php echo $product; ?></div>
            </div>

            <a class="app-card-link-mask" href="<?php echo web_root; ?>admin/products/index.php"></a>
        </div>
    </div>

    <!-- <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Number of Orders</h4>
                <div class="stats-figure"><?php echo $order; ?></div>
            </div>

            <a class="app-card-link-mask" href="<?php echo web_root; ?>admin/orders/index.php"></a>
        </div>
    </div> -->

    <!-- <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Received Orders</h4>
                <div class="stats-figure"><?php echo $receive; ?></div>
            </div>

            <a class="app-card-link-mask" href="<?php echo web_root; ?>admin/orders/index.php"></a>
        </div>
    </div> -->
                                <!-- in-store sales -->
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"> <i class="fas fa-store mr-2" style="color: #1cc88a;"></i> In-Store Revenue</h4>
                <div class="stats-figure"> <?php echo number_format($totalRevenue, 2); ?></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"> <i class="fas fa-shopping-cart mr-2" style="color: #36b9cc;"></i> Online Sales Revenue</h4>
                <div class="stats-figure"><?php echo number_format($totalRevenueOnline, 2); ?></div>
            </div>
        </div>
    </div>
                  <!-- pending -->
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"><i class="fas fa-clock mr-2"  style="color: #f6c23e;"></i> Pending</h4>
                <div class="stats-figure"><?php echo $statusCounts['pending_count']; ?></div>
            </div>
        </div>
    </div>
                       <!-- preparing -->
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"><i class="fas fa-box-open mr-2"  style="color: #e74a3b;"></i> Preparing for Delivery</h4>
                <div class="stats-figure"><?php echo $statusCounts['preparing_count']; ?></div>
            </div>
        </div>
    </div>
                         <!-- approaching -->
    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"><i class="fas fa-truck mr-2" style="color: #5a5c69;"></i> Approaching Destination</h4>
                <div class="stats-figure"><?php echo $statusCounts['approaching_count']; ?></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1"> <img src="../../images/gcash.png" height="70" width="120"></h4>
                <div class="stats-figure">₱<?php echo number_format($gcashTotal, 2); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="row g-4 mb-4">
<div class="col-12 col-lg-6">
    <div class="app-card app-card-stat shadow-sm h-100">
        <div class="app-card-body p-3 p-lg-4">
            <div class="d-flex justify-content-between mb-3">
                <select id="yearSelect" onchange="updateChart()" class="form-select form-select-sm" style="width: auto;">
                    <!-- Years will be populated dynamically -->
                </select>
                <select id="monthSelect" onchange="updateChart()" class="form-select form-select-sm" style="width: auto;">
                    <option value="overall">Overall</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div id="chartContainer" style="height: 250px;"></div>
        </div>
    </div>
</div>

    <div class="col-12 col-lg-6">
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-3">Product</h4>
                <div id="doughnutChartContainer" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
    <div class="app-card app-card-stat shadow-sm h-100">
        <div class="app-card-body p-3 p-lg-4">
            <h4 class="stats-type mb-3">In-Store Best Selling Products</h4>
            <div id="productSalesChartContainer" style="height: 300px;"></div>
            <a href="<?php echo web_root; ?>admin/topproducts/index.php" class="text-dark">Click here see the Higher to In-store sells products</a>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="app-card app-card-stat shadow-sm h-100">
        <div class="app-card-body p-3 p-lg-4">
            <h4 class="stats-type mb-3"> Online Best Selling Products</h4>
            <div id="onlineSalesChartContainer" style="height: 300px;"></div>
            <a href="<?php echo web_root; ?>admin/topproducts/index.php" class="text-dark">Click here see the Higher to low Online sells products</a>
        </div>
    </div>
</div>
</div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    
    <script>
function populateYearSelector() {
    var currentYear = new Date().getFullYear();
    var yearSelect = document.getElementById('yearSelect');
    
    // Add last 5 years to the selector
    for (var i = 0; i < 5; i++) {
        var year = currentYear - i;
        var option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }
}

function updateChart() {
    var year = document.getElementById('yearSelect').value;
    var month = document.getElementById('monthSelect').value;
    
    fetch(`get_data.php?year=${year}&month=${month}`)
        .then(response => response.json())
        .then(data => {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: "Orders Sales Overview"
                },
                axisY: {
                    title: "(PHP)",
                    includeZero: true,
                    prefix: "₱",
                    suffix:  "k"
                },
                data: [{
                    type: "bar",
                    yValueFormatString: "₱#,##0K",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "white",
                    color: "#fd2323",
                    dataPoints: data
                }]
            });
            chart.render();
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', function() {
    populateYearSelector();
    updateChart(); // Initial chart update
});

function fetchStockData() {
    fetch('get_stock.php')
        .then(response => response.json())
        .then(data => {
            updateDoughnutChart(data);
        })
        .catch(error => console.error('Error fetching stock data:', error));
}

function updateDoughnutChart(stockData) {
    var chart = new CanvasJS.Chart("doughnutChartContainer", {
        animationEnabled: true,
        title: {
            text: "Stock Forecasting"
        },
        data: [{
            type: "doughnut",
            startAngle: 60,
            indexLabelFontSize: 12,
            indexLabel: "{label} - {y}",
            toolTipContent: "<b>{label}:</b> {y} ({percent}%)",
            dataPoints: stockData.map(item => ({
                label: item.productName,
                y: parseInt(item.productStock)
            }))
        }]
    });
    chart.render();
}

function fetchProductSalesData() {
    fetch('get_product_sales.php')
        .then(response => response.json())
        .then(data => {
            updateProductSalesChart(data);
        })
        .catch(error => console.error('Error fetching product sales data:', error));
}

function updateProductSalesChart(salesData) {
    // Limit to top 4 products
    salesData = salesData.slice(0, 4);

    var colors = [
        "#26547c", "#ef476f", "#ffd166", "#06d6a0"
    ];

    salesData.forEach((dataPoint, index) => {
        dataPoint.color = colors[index];
    });

    var chart = new CanvasJS.Chart("productSalesChartContainer", {
        animationEnabled: true,
        title: {
            text: "Top Best-Selling Products In-Store"
        },
        axisY: {
            title: "Products Sold",
            includeZero: true
        },
        axisX: {
            interval: 1,
            labelFormatter: function() {
                return ""; // This removes the labels on the x-axis
            }
        },
        dataPointWidth: 60,  // Increased width for better visibility
        toolTip: {
            content: "<strong>{label}</strong><br/>Sold: {y}"
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: salesData
        }]
    });
    chart.render();
}

function fetchOnlineSalesData() {
    fetch('get_online_sales.php')
        .then(response => response.json())
        .then(data => {
            updateOnlineSalesChart(data);
        })
        .catch(error => console.error('Error fetching product sales data:', error));
}

function updateOnlineSalesChart(salesData) {
    // Limit to top 4 products
    salesData = salesData.slice(0, 4);

    var colors = [
        "#231f20", "#bb4430", "#7ebdc2", "#f3dfa2"
    ];

    salesData.forEach((dataPoint, index) => {
        dataPoint.color = colors[index];
    });

    var chart = new CanvasJS.Chart("onlineSalesChartContainer", {
        animationEnabled: true,
        title: {
            text: "Top Best-Selling Online Products"
        },
        axisY: {
            title: "Products Sold",
            includeZero: true
        },
        axisX: {
            interval: 1,
            labelFormatter: function() {
                return ""; // This removes the labels on the x-axis
            }
        },
        dataPointWidth: 60,  // Increased width for better visibility
        toolTip: {
            content: "<strong>{label}</strong><br/>Sold: {y}"
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: salesData
        }]
    });
    chart.render();
}
function initializeCharts() {
    updateChart();
    fetchStockData();
    fetchProductSalesData();
    fetchOnlineSalesData();
}

window.onload = initializeCharts;
</script>

