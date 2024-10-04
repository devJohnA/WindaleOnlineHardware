<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';
    include '../admin/dbcon/conn.php';
    // $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fname = $conn->real_escape_string($_POST['FNAME']);
        $lname = $conn->real_escape_string($_POST['LNAME']);
        $cityadd = $conn->real_escape_string($_POST['CITYADD']);
        $lmark = $conn->real_escape_string($_POST['LMARK']);
        $gender = $conn->real_escape_string($_POST['GENDER']);
        $phone = $conn->real_escape_string($_POST['PHONE']);
        $cusuname = $conn->real_escape_string($_POST['CUSUNAME']);
        $password = sha1($_POST['CUSPASS']);
        $term = 1;
        $datejoin = date('Y-m-d H:i:s');
        $code = $conn->real_escape_string(md5(rand()));

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblcustomer WHERE CUSUNAME='{$cusuname}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$cusuname} - This username already exists.</div>";
        } else {    
                $sql = "INSERT INTO tblcustomer (FNAME, LNAME, CITYADD, LMARK, GENDER, PHONE, CUSUNAME, CUSPASS, TERMS, DATEJOIN, code) 
                        VALUES ('$fname', '$lname', '$cityadd', '$lmark', '$gender', '$phone', '$cusuname', '$password', '$term', '$datejoin', '$code')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    $mail = new PHPMailer(true);

                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'delacruzjohnanthon@gmail.com';
                        $mail->Password   = 'rpabbkqjeldjveyr';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;

                        $mail->setFrom('delacruzjohnanthon@gmail.com');
                        $mail->addAddress($cusuname);

                        $mail->isHTML(true);
                        $mail->Subject = 'Windale Hardware Inc.';
                        $mail->Body    = 'Here is the verification link <b><a href="http://localhost/Capstone2/onlinecustomer/?verification='.$code.'">http://localhost/Capstone2/onlinecustomer/?verification='.$code.'</a></b>';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something went wrong.</div>";
                }
        }
    }
?>
