<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to select all records from tbluseraccount
$sqlSelect = "SELECT * FROM tbluseraccount";

// Execute the query to select all records
$result = $conn->query($sqlSelect);

if ($result->num_rows > 0) {
    // Output each row
    while($row = $result->fetch_assoc()) {
        echo "USERID: " . $row["USERID"] . "<br>";
        echo "U_NAME: " . $row["U_NAME"] . "<br>";
        echo "U_USERNAME: " . $row["U_USERNAME"] . "<br>";
        echo "U_CON: " . $row["U_CON"] . "<br>";
        echo "U_EMAIL: " . $row["U_EMAIL"] . "<br>";
        echo "U_ROLE: " . $row["U_ROLE"] . "<br>";
        echo "USERIMAGE: " . $row["USERIMAGE"] . "<br>";
        echo "Code: " . $row["Code"] . "<br>";
        echo "SECRET_KEY: " . $row["SECRET_KEY"] . "<br>";
        echo "IS_2FA_VERIFIED: " . $row["IS_2FA_VERIFIED"] . "<br><br>";
    }
} else {
    echo "No records found in tbluseraccount.<br>";
}

// Close the connection
$conn->close();
?>
