<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sqlDropTable = "DROP TABLE IF EXISTS tbluseraccount";

// Execute the query to drop the table
if ($conn->query($sqlDropTable) === TRUE) {
    echo "Table tbluseraccount dropped successfully!<br>";
} else {
    echo "Error dropping table: " . $conn->error . "<br>";
}
// Close the connection
$conn->close();
?>
