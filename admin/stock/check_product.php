<?php
require_once '../../admin/dbcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $conn->real_escape_string($_POST['productName']);
    
    $sql = "SELECT COUNT(*) as count FROM stocks WHERE productName = '$productName'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    echo json_encode(['exists' => $row['count'] > 0]);
}

$conn->close();
?>