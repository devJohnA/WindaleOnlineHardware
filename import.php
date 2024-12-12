<?php

$servername = "127.0.0.1";
$username = "u510162695_bantayanisland";
$password = "1Bantayan"; 
$dbname = "u510162695_bantayanisland"; 

if (isset($_POST['import'])) {
    // Check if a file was uploaded
    if (isset($_FILES['sqlFile']) && $_FILES['sqlFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['sqlFile']['tmp_name'];
        $fileContent = file_get_contents($fileTmpPath);

        // Establish connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Split the file into individual SQL statements
        $queries = explode(';', $fileContent);
        $success = true;

        // Execute each SQL statement
        foreach ($queries as $query) {
            $trimmedQuery = trim($query);
            if (!empty($trimmedQuery)) {
                if (!$conn->query($trimmedQuery)) {
                    echo "Error executing query: " . $conn->error . "<br>";
                    $success = false;
                }
            }
        }

        if ($success) {
            echo "Database imported successfully!";
        } else {
            echo "Some queries failed to execute.";
        }

        $conn->close();
    } else {
        echo "No file uploaded or upload error.";
    }
} else {
    echo "Invalid request.";
}
?>
