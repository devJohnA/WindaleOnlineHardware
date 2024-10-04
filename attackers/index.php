<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Logs</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    $conn = new mysqli('localhost', 'u510162695_dried', '1Dried_password', 'u510162695_dried');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>

    <h2>SQL Injection Attempts</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
        </tr>
        <?php
        $sql = "SELECT id, ip_address FROM tbl_sql_injection_logs";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["ip_address"]) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No SQL injection attempts logged</td></tr>";
        }
        ?>
    </table>

    <h2>XSS Attempts</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
        </tr>
        <?php
        $sql = "SELECT id, ip_address FROM xss_attempts";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["ip_address"]) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No XSS attempts logged</td></tr>";
        }
        ?>
    </table>

    <h2>Admin SQL Injection Detected</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
        </tr>
        <?php
        $sql = "SELECT id, ip_address FROM sql_injection_attempts";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["ip_address"]) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No SQL injection attempts logged</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>