<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to drop the tblcustomer table
$sqlDrop = "DROP TABLE IF EXISTS tbluseraccount;";

// Execute query to drop the table
if ($conn->query($sqlDrop) === TRUE) {
    echo "Table tbluseraccount dropped successfully!";
} else {
    echo "Error dropping table: " . $conn->error;
}

// Close connection
$conn->close();
?>
