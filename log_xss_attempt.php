<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USER', 'u510162695_dried');
define('DB_PASS', '1Dried_password');
define('DB_NAME', 'u510162695_dried');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Get the IP address
$ip_address = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO xss_attempts (ip_address, attempted_name, attempted_review, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $ip_address, $data['name'], $data['review']);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "XSS attempt logged"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error logging XSS attempt"]);
}

$stmt->close();
$conn->close();
?>