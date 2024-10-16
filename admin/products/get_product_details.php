<?php
require '../../admin/dbcon/conn.php';

if(isset($_GET['productName'])) {
    $productName = $_GET['productName'];
    
    $query = "SELECT s.productCategory, s.productPrice, s.productStock, s.images, c.CATEGORIES
              FROM stocks s
              LEFT JOIN tblcategory c ON s.productCategory = c.CATEGID
              WHERE s.productName = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        // Use CATEGORIES as the category name, fall back to productCategory if CATEGORIES is null
        $row['productCategory'] = $row['CATEGORIES'] ?? $row['productCategory'];
        unset($row['CATEGORIES']);
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "Product not found"));
    }
} else {
    echo json_encode(array("error" => "No product name provided"));
}
?>