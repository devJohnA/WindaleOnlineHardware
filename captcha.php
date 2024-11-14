<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Logs</title>
</head>
<body>

<h2>reCAPTCHA Logs</h2>

<?php
include 'admin/dbcon/conn.php'; // Ensure your database connection is correct

// Query the tbl_recaptcha_logs table
$query = "SELECT * FROM tbl_recaptcha_logs ORDER BY attempt_date DESC";
$result = $mydb->executeQuery($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>IP Address</th><th>Score</th><th>Attempt Date</th></tr>";

    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ip_address']) . "</td>";
        echo "<td>" . htmlspecialchars($row['score']) . "</td>";
        echo "<td>" . htmlspecialchars($row['attempt_date']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No records found.";
}
?>

</body>
</html>
