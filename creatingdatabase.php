<?php

$servername = "localhost";
$username = "u510162695_dried"; 
$password = "1Dried_password"; 
$dbname = "u510162695_dried"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
CREATE TABLE IF NOT EXISTS tblcustomer (
  CUSTOMERID int(11) NOT NULL AUTO_INCREMENT,
  FNAME varchar(30) NOT NULL,
  LNAME varchar(30) NOT NULL,
  MNAME varchar(30) NOT NULL,
  CUSHOMENUM varchar(90) NOT NULL,
  STREETADD text NOT NULL,
  BRGYADD text NOT NULL,
  CITYADD text NOT NULL,
  LMARK varchar(50) NOT NULL,
  PROVINCE varchar(80) NOT NULL,
  COUNTRY varchar(30) NOT NULL,
  DBIRTH date NOT NULL,
  GENDER varchar(10) NOT NULL,
  PHONE varchar(20) NOT NULL,
  EMAILADD varchar(40) NOT NULL,
  ZIPCODE int(6) NOT NULL,
  CUSUNAME varchar(250) NOT NULL,
  CUSPASS varchar(90) NOT NULL,
  CUSPHOTO varchar(255) NOT NULL,
  TERMS tinyint(4) NOT NULL,
  STATUS varchar(250) NOT NULL,
  DATEJOIN timestamp NOT NULL DEFAULT current_timestamp(),
  code text NOT NULL,
  OTP varchar(6) DEFAULT NULL,
  OTP_TIMESTAMP timestamp NULL DEFAULT NULL,
  PRIMARY KEY (CUSTOMERID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
";

// Execute query
if ($conn->query($sql) === TRUE) {
    echo "Table tblcustomer created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

// SQL to insert data
$sqlInsert = "
INSERT INTO tblcustomer (CUSTOMERID, FNAME, LNAME, MNAME, CUSHOMENUM, STREETADD, BRGYADD, CITYADD, LMARK, PROVINCE, COUNTRY, DBIRTH, GENDER, PHONE, EMAILADD, ZIPCODE, CUSUNAME, CUSPASS, CUSPHOTO, TERMS, STATUS, DATEJOIN, code, OTP, OTP_TIMESTAMP) 
VALUES 
(112, 'John Anthon', 'Dela Cruz', '', '', '', '', 'Mancilang. Madridejos, Cebu', 'Purok Tulingan, lotohan dapit', '', '', '0000-00-00', 'Male', '09692870485', '', 0, 'delacruzjohnanthon@gmail.com', '$2y$10$Sekgaup7yUI.Gl.dRHVHVeKs4jLeN8MuSFopVAmPowzbauu/ZgeQS', '', 1, '', '2024-11-14 08:12:53', '', NULL, NULL);
";

// Execute data insertion
if ($conn->query($sqlInsert) === TRUE) {
    echo "Data inserted successfully!";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Close connection
$conn->close();
?>
