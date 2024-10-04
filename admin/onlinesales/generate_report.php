<?php
require_once '../../admin/dbcon/conn.php';

$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];

if (!empty($dateFrom) && !empty($dateTo)) {
    // Convert input dates to ensure no time component is considered
    $dateFrom .= ' 00:00:00';
    $dateTo .= ' 23:59:59';

    // Prepare the query with datetime bounds
    $query = "SELECT 
        o.PROID,
        p.PRODESC,
        SUM(o.ORDEREDQTY) AS TotalQuantity,
        SUM(o.ORDEREDPRICE) AS TotalOrderedPrice,
        s.ORDEREDDATE
    FROM 
        tblorder o
    JOIN 
        tblproduct p ON o.PROID = p.PROID
    JOIN
        tblsummary s ON o.ORDEREDNUM = s.ORDEREDNUM
    WHERE 
        s.ORDEREDDATE BETWEEN ? AND ?
    GROUP BY 
        o.PROID, p.PRODESC
    ORDER BY 
        TotalOrderedPrice DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $dateFrom, $dateTo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<style>
        @media screen, print {
            .custom-table {
                width: 100%;
                border-collapse: collapse;
            }
            .custom-table thead th {
                background-color: #fd2323 !important;
                color: white !important;
                padding: 10px;
            }
            .custom-table td {
                border-bottom: 1px solid #black !important;
                padding: 8px;
            }
            @media print {
                .custom-table {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .logo {
            height: 100px;
        }
        .mb {
            margin-bottom: 7px;
        }
        </style>';

        // Format and print header
        $fromDate = new DateTime($_POST['dateFrom']);
        $toDate = new DateTime($_POST['dateTo']);
        echo '<div class="header-container">';
        echo '<div>';
        echo '<p class="mb">Company Name: Windale Hardware</p>';
        echo '<p class="mb">Date Range: ' . $fromDate->format('F j, Y') . ' to ' . $toDate->format('F j, Y') . '</p>';
        echo '</div>';
        echo '<img src="../../img/windalelogo.jpg" alt="Windale Logo" class="logo">';
        echo '</div>';

        echo '<table class="table custom-table">';
        echo '<thead><tr><th>Product ID</th><th>Product Description</th><th>Total Quantity</th><th>Total Ordered Price</th><th>Order Date</th></tr></thead><tbody>';

        $overallTotalPrice = 0;
        while ($row = $result->fetch_assoc()) {
            $overallTotalPrice += $row['TotalOrderedPrice'];
            $orderDate = new DateTime($row['ORDEREDDATE']);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['PROID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['PRODESC']) . "</td>";
            echo "<td>" . htmlspecialchars($row['TotalQuantity']) . "</td>";
            echo "<td>&#8369;" . htmlspecialchars(number_format($row['TotalOrderedPrice'], 2)) . "</td>";
            echo "<td>" . $orderDate->format('F j, Y H:i') . "</td>";
            echo "</tr>";
        }

        echo '</tbody></table>';
        echo '<div class="text-end" style="text-align: right;"><strong>Overall Total Price: </strong><br>&#8369;' . 
             htmlspecialchars(number_format($overallTotalPrice, 2)) . '</div>';
    } else {
        echo '<p class="text-center">No records found for the selected date range.</p>';
    }

    $stmt->close();
} else {
    echo '<p class="text-center">Invalid date range.</p>';
}

$conn->close();
?>