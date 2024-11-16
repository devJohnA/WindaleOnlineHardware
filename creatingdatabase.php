<?php
// Database connection details
$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";  // Debugging line to ensure connection works

// SQL query to add the OTP and OTP_TIMESTAMP columns to tbluseraccount
$sql = "ALTER TABLE `tbluseraccount`
    ADD COLUMN `OTP` varchar(6) DEFAULT NULL, 
    ADD COLUMN `OTP_TIMESTAMP` timestamp NULL DEFAULT NULL;";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Columns added successfully to tbluseraccount";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
