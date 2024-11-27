<?php
header('Access-Control-Allow-Origin: *'); // Allow all origins for now (adjust as needed for security).
header('Content-Type: application/json'); // Set correct response type.

$servername = "localhost";
$username = "u510162695_dried";
$password = "1Dried_password";
$dbname = "u510162695_dried";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Set proper error response code
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT CUSTOMERID as id, FNAME as first_name, LNAME as last_name, DATEJOIN as signup_date FROM tblcustomer";
$result = $conn->query($sql);

$customers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

$conn->close();
echo json_encode($customers);
?>
