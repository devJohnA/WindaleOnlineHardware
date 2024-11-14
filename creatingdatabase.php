<?php
// Database connection variables
$servername = "localhost";
$username = "u510162695_dried";
$password = "1Dried_password";
$dbname = "u510162695_dried";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create a new table
$sql = "CREATE TABLE `tbl_recaptcha_logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `ip_address` VARCHAR(45),
    `score` FLOAT,
    `attempt_date` DATETIME
)";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Table 'ExampleTable' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close connection
$conn->close();
?>
