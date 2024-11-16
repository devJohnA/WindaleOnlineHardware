<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to select all records from login_logs
$sqlSelect = "SELECT * FROM login_logs";

// Execute the query to select all records
$result = $conn->query($sqlSelect);

if ($result->num_rows > 0) {
    // Output each row
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . "<br>";
        echo "USERID: " . $row["USERID"] . "<br>";
        echo "Login Time: " . $row["login_time"] . "<br>";
        echo "Status: " . $row["status"] . "<br><br>";
    }
} else {
    echo "No records found in login_logs.<br>";
}

// Close the connection
$conn->close();
?>
