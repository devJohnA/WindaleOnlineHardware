<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create tbluseraccount table
$sqlCreateTable = "
CREATE TABLE IF NOT EXISTS `tbluseraccount` (
  `USERID` int(11) NOT NULL AUTO_INCREMENT,
  `U_NAME` varchar(122) NOT NULL,
  `U_USERNAME` varchar(122) NOT NULL,
  `U_CON` varchar(11) NOT NULL,
  `U_EMAIL` varchar(225) NOT NULL,
  `U_PASS` varchar(122) NOT NULL,
  `U_ROLE` varchar(30) NOT NULL,
  `USERIMAGE` varchar(255) NOT NULL,
  `Code` varchar(250) NOT NULL,
  `SECRET_KEY` varchar(255) NOT NULL,
  `IS_2FA_VERIFIED` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
";

// Execute the query to create the table
if ($conn->query($sqlCreateTable) === TRUE) {
    echo "Table tbluseraccount created successfully!<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// SQL to insert data into tbluseraccount
$sqlInsertData = "
INSERT INTO `tbluseraccount` (`USERID`, `U_NAME`, `U_USERNAME`, `U_CON`, `U_EMAIL`, `U_PASS`, `U_ROLE`, `USERIMAGE`, `Code`, `SECRET_KEY`, `IS_2FA_VERIFIED`) 
VALUES
(11, 'Dante Montecalvo', 'dante@gmail.com', '09091296064', '', '$2y$10$Ga76I/ZywTd0krj57dCvOu031aoocYIeCJ02S1v6gAKZmUUCH4CSu', 'Staff', '', '', '', 0),
(15, 'John Anthon Dela Cruz', 'delacruzjohnanthon@gmail.com', '09692870485', '', '$2y$10$fHsiEuaLAQYlRfITjPQ.qO8AjrTptDHFxMlO8.J./n92Q8hlfHxKG', 'Administrator', '', '', '3XNZZCLV4KHWQKEO', 0);
";

// Execute the data insertion query
if ($conn->query($sqlInsertData) === TRUE) {
    echo "Data inserted into tbluseraccount successfully!<br>";
} else {
    echo "Error inserting data: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
