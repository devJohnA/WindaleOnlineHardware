<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create the login_logs table
$sqlCreateTable = "
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `USERID` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `USERID` (`USERID`),
  CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`USERID`) REFERENCES `tbluseraccount` (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

// Execute the query to create the table
if ($conn->query($sqlCreateTable) === TRUE) {
    echo "Table login_logs created successfully!<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// SQL to insert data into login_logs
$sqlInsertData = "
INSERT INTO `login_logs` (`id`, `USERID`, `login_time`, `status`) 
VALUES
(1, 15, '2024-11-16 12:12:24', 'success'),
(2, 15, '2024-11-16 12:25:23', 'success'),
(3, 15, '2024-11-16 12:28:36', 'success'),
(4, 15, '2024-11-16 12:28:42', 'failed_2fa'),
(5, 15, '2024-11-16 12:47:32', 'success'),
(6, 15, '2024-11-16 13:04:50', 'failed_2fa'),
(7, 15, '2024-11-16 13:05:07', 'failed_2fa'),
(8, 15, '2024-11-16 13:08:38', 'success'),
(9, 15, '2024-11-16 14:55:39', 'failed_2fa'),
(10, 15, '2024-11-16 14:55:47', 'failed_2fa'),
(11, 15, '2024-11-16 14:55:57', 'success');
";

// Execute the data insertion query
if ($conn->query($sqlInsertData) === TRUE) {
    echo "Data inserted into login_logs successfully!<br>";
} else {
    echo "Error inserting data: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
