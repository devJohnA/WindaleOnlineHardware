<?php

if (!isset($_SESSION['USERID'])) {

  redirect(web_root . "admin/index.php");

}

?>

<?php

$user = new User();

$singleuser = $user->single_user($_SESSION['USERID']);



?>


<style>


.border-bottom-red {
    border-bottom: 3px solid #fd2323;
}

</style>

<div class="row g-4 mb-4">
    <div class="col-12">  <!-- Changed from col-12 col-lg-6 to col-12 -->
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-3">In-Store Best Products</h4>
                <div id="productSalesChartContainer" style="height: 250px;"></div>  <!-- Increased height -->
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">  <!-- Changed from col-12 col-lg-6 to col-12 -->
        <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-3">Top Online Product</h4>
                <div id="onlineSalesChartContainer" style="height: 250px;"></div>  <!-- Increased height -->
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    
    <script>


function fetchProductSalesData() {
    fetch('get_product_sales.php')
        .then(response => response.json())
        .then(data => {
            updateProductSalesChart(data);
        })
        .catch(error => console.error('Error fetching product sales data:', error));
}

function updateProductSalesChart(salesData) {
    // Create an array of distinct colors
    var colors = [
        "#26547c", "#ef476f", "#ffd166", "#06d6a0", "#9C27B0",
        "#795548", "#3F51B5", "#009688", "#FF9800", "#607D8B"
    ];

    // Assign colors to dataPoints
    salesData.forEach((dataPoint, index) => {
        dataPoint.color = colors[index % colors.length];
    });

    var chart = new CanvasJS.Chart("productSalesChartContainer", {
        animationEnabled: true,
        title: {
            text: "Best-Selling Products In-Store"
        },
        axisY: {
            title: "Product Sold",
            includeZero: true
        },
        axisX: {
            interval: 1,
            labelFormatter: function() {
                return ""; // This removes the labels on the x-axis
            }
        },
        dataPointWidth: 50,
        toolTip: {
            content: "{label}: {y} total" // This defines what's shown on hover
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0 ",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: salesData
        }]
    });
    chart.render();
}

//for online chart

function fetchOnlineSalesData() {
    fetch('get_online_sales.php')
        .then(response => response.json())
        .then(data => {
            updateOnlineSalesChart(data);
        })
        .catch(error => console.error('Error fetching product sales data:', error));
}

function updateOnlineSalesChart(salesData) {
    // Create an array of distinct colors
    var colors = [
        "#231f20", "#bb4430", "#7ebdc2", "#f3dfa2", "#9C27B0",
        "#795548", "#3F51B5", "#009688", "#FF9800", "#607D8B"
    ];

    // Assign colors to dataPoints
    salesData.forEach((dataPoint, index) => {
        dataPoint.color = colors[index % colors.length];
    });

    var chart = new CanvasJS.Chart("onlineSalesChartContainer", {
        animationEnabled: true,
        title: {
            text: "Best-Selling Products Online"
        },
        axisY: {
            title: "Product Sold",
            includeZero: true
        },
        axisX: {
            interval: 1,
            labelFormatter: function() {
                return ""; // This removes the labels on the x-axis
            }
        },
        dataPointWidth: 50,
        toolTip: {
            content: "{label}: {y} total" // This defines what's shown on hover
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0 ",
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
    fetchProductSalesData();
    fetchOnlineSalesData();
}

window.onload = initializeCharts;
</script>

