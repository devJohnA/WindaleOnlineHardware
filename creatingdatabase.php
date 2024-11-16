<?php
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

// SQL to check if columns already exist
$checkColumns = "SHOW COLUMNS FROM tblcustomer LIKE 'attempts'";
$result = $conn->query($checkColumns);

if ($result->num_rows == 0) {
    // Columns don't exist, so add them
    $alterTable = "ALTER TABLE tblcustomer 
                   ADD COLUMN attempts INT DEFAULT 0,
                   ADD COLUMN last_attempt DATETIME DEFAULT NULL";
    
    if ($conn->query($alterTable) === TRUE) {
        echo "Columns 'attempts' and 'last_attempt' added successfully to tblcustomer table.";
    } else {
        echo "Error adding columns: " . $conn->error;
    }
} else {
    echo "Columns already exist in the table.";
}

// Close the connection
$conn->close();
?>