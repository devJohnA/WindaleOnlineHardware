<?php
require '../admin/dbcon/conn.php';

if (isset($_GET['VERIFICATIONCODE'])) {
    $token = $_GET['VERIFICATIONCODE'];
    $sql = "SELECT * FROM tblcustomer WHERE VERIFICATIONCODE='$token' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE tblcustomer SET VERIFIED=1, VERIFICATIONCODE='' WHERE VERIFICATIONCODE='$token'";
        if ($conn->query($sql)) {
            echo "Your email has been verified. You can now <a href='index.php'>login</a>.";
        } else {
            echo "Verification failed: " . $conn->error;
        }
    } else {
        echo "Invalid token.";
    }
}
?>
