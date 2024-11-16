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

// Query to fetch all records from tblcustomer
$sql = "SELECT * FROM tblcustomer";
$result = $conn->query($sql);

// Check if any records are returned
if ($result->num_rows > 0) {
    // Loop through the records and output data
    while ($row = $result->fetch_assoc()) {
        // Displaying each record (example: echoing each column)
        echo "Customer ID: " . $row["CUSTOMERID"] . "<br>";
        echo "First Name: " . $row["FNAME"] . "<br>";
        echo "Middle Name: " . $row["MNAME"] . "<br>";
        echo "Last Name: " . $row["LNAME"] . "<br>";
        echo "Home Number: " . $row["CUSHOMENUM"] . "<br>";
        echo "Street Address: " . $row["STREETADD"] . "<br>";
        echo "Barangay Address: " . $row["BRGYADD"] . "<br>";
        echo "City Address: " . $row["CITYADD"] . "<br>";
        echo "Landmark: " . $row["LMARK"] . "<br>";
        echo "Province: " . $row["PROVINCE"] . "<br>";
        echo "Country: " . $row["COUNTRY"] . "<br>";
        echo "Date of Birth: " . $row["DBIRTH"] . "<br>";
        echo "Gender: " . $row["GENDER"] . "<br>";
        echo "Phone: " . $row["PHONE"] . "<br>";
        echo "Email Address: " . $row["EMAILADD"] . "<br>";
        echo "Zip Code: " . $row["ZIPCODE"] . "<br>";
        echo "Username: " . $row["CUSUNAME"] . "<br>";
        echo "Password: " . $row["CUSPASS"] . "<br>";
        echo "Photo: " . $row["CUSPHOTO"] . "<br>";
        echo "Terms: " . $row["TERMS"] . "<br>";
        echo "Status: " . $row["STATUS"] . "<br>";
        echo "Date Joined: " . $row["DATEJOIN"] . "<br>";
        echo "Code: " . $row["code"] . "<br>";
        echo "OTP: " . $row["OTP"] . "<br>";
        echo "OTP Timestamp: " . $row["OTP_TIMESTAMP"] . "<br><br>";
    }
} else {
    echo "0 results found";
}

// Close the connection
$conn->close();
?>
