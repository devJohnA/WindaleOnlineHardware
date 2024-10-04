<?php
require_once '../dbcon/conn.php';

$query = "SELECT p.PRODESC, SUM(o.ORDEREDQTY) as total_sold
          FROM tblorder o
          JOIN tblproduct p ON o.PROID = p.PROID
          GROUP BY o.PROID
          ORDER BY total_sold DESC
          LIMIT 10";

$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        "label" => $row['PRODESC'],
        "y" => intval($row['total_sold'])
    );
}

echo json_encode($data);

$conn->close();
?>