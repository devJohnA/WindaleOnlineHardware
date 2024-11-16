<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DROP TABLE IF EXISTS tblcustomer";

if ($conn->query($sql) === TRUE) {
    echo "Table tblcustomer was successfully dropped.";
} else {
    echo "Error dropping table: " . $conn->error;
}

// Close connection
$conn->close();
?>
