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

// Query to delete all records from tblproduct
$deleteQuery = "DELETE FROM tblproduct";

// Execute the query to delete all records
if ($conn->query($deleteQuery) === TRUE) {
    echo "All records in 'tblproduct' table have beenaasdsadasdsd asddeleted successfully.";
} else {
    echo "Error deleting records: " . $conn->error;
}

// Close the connection
$conn->close();
?>
