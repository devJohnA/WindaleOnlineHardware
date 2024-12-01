<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$servername = "localhost";
$username = "u510162695_dried";
$password = "1Dried_password";
$dbname = "u510162695_dried";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT CUSTOMERID as id, FNAME as first_name, LNAME as last_name, DATEJOIN as signup_date FROM tblcustomer";
$result = $conn->query($sql);

$customers = [];
$suspicious_entries = [];

while ($row = $result->fetch_assoc()) {
    $is_suspicious = false;

    // Check for empty or unusual values (e.g., scripts in names)
    if (empty($row['first_name']) || empty($row['last_name']) || preg_match('/<script|onerror|alert|javascript:/i', implode(' ', $row))) {
        $is_suspicious = true;
        $suspicious_entries[] = $row;
    }

    $customers[] = [
        "id" => $row['id'],
        "first_name" => htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') ?: 'N/A',
        "last_name" => htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') ?: 'N/A',
        "signup_date" => htmlspecialchars($row['signup_date'], ENT_QUOTES, 'UTF-8') ?: 'Unknown'
    ];

    // Log suspicious entries (or write to a file or database for auditing)
    if ($is_suspicious) {
        error_log("Suspicious entry detected: " . json_encode($row));
    }
}

$response = [
    "total_signups" => count($customers),
    "customers" => $customers,
    "suspicious_count" => count($suspicious_entries)
];

$conn->close();
echo json_encode($response);
?>
