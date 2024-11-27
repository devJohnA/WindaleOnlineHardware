<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database connection
$servername = "localhost";
$username = "u510162695_dried";
$password = "1Dried_password";
$dbname = "u510162695_dried";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "success" => false, 
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

$username = $conn->real_escape_string($data['username']);
$provided_password = $data['password'];

// Prepare SQL to prevent SQL injection
$sql = "SELECT USERID, U_USERNAME, U_PASS FROM tbluseraccount WHERE U_USERNAME = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password using password_verify
    if (password_verify($provided_password, $user['U_PASS'])) {
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "userId" => $user['USERID'],
            "username" => $user['U_USERNAME']
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Invalid username or password"
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "message" => "User not found"
    ]);
}

$stmt->close();
$conn->close();
?>