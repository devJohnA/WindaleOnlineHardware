<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json'); 

$servername = "localhost";
$username = "u510162695_dried";
$password = "1Dried_password";
$dbname = "u510162695_dried";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT CUSTOMERID as id, FNAME as first_name, LNAME as last_name, DATEJOIN as signup_date FROM tblcustomer";
$result = $conn->query($sql);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

// Send total count and customer data
$response = [
    "total_signups" => count($customers),
    "customers" => $customers
];

$conn->close();
echo json_encode($response);
?>
