<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Names</title>
</head>
<body>

<h2>Customer Names</h2>

<?php
include 'admin/dbcon/conn.php'; // Include your database connection

// Query to select only the first and last names
$query = "SELECT FNAME, LNAME FROM tblcustomer";
$result = $mydb->executeQuery($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>First Name</th><th>Last Name</th></tr>";

    // Loop through each record and display the first and last names
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['FNAME']) . "</td>";
        echo "<td>" . htmlspecialchars($row['LNAME']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No records found.";
}
?>

</body>
</html>
