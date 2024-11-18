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

// Create the email_verifications table if it doesn't exist
$tableQuery = "
    CREATE TABLE IF NOT EXISTS email_verifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        expiry INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";

// Execute the query to create the table
if ($conn->query($tableQuery) === TRUE) {
    echo "Table 'email_verifications' created successfully (if it didn't exist).";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>
