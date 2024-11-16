<?php
// Database connection details
$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to add the SECRET_KEY column
$sql = "ALTER TABLE tbluseraccount ADD COLUMN SECRET_KEY varchar(255) NOT NULL;";

// Execute the query to add the column
if ($conn->query($sql) === TRUE) {
    echo "Column 'SECRET_KEY' added successfully!";
} else {
    echo "Error adding column: " . $conn->error;
}

// Close the connection
$conn->close();
?>
