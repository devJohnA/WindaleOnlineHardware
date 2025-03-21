<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require("../config.php");
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
?>
<?php 
session_start();

$email = "";
$name = "";
$errors = array();


//connect to database
$con = mysqli_connect('localhost', 'u510162695_dried', '1Dried_password', 'u510162695_dried');

//for verified email


    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM tblcustomer WHERE CUSUNAME='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(100000, 999999);
            $insert_code = "UPDATE tblcustomer SET ZIPCODE = $code WHERE CUSUNAME = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Reset Password Notification";
                $message = "<h2>windale Hardware inc.</h2>
                <p>This is your OTP code:  <b>$code</b> <br><br>
                    Please use this code to set your new password.<br><br>
                    If you didn't request this code, you can disregard this message.
                </p>
                ";
                $sender = "delacruzjohnanthon@gmail.com";
                //Load composer's autoloader

// $insert_data = "INSERT INTO `messagein` (`Id`, `SendTime`, `MessageFrom`, `MessageTo`, `MessageText`) VALUES ('', '', 'MPLA', '$email', 'OTP code is $code')";
//         $data_check = mysqli_query($con, $insert_data);

    $mail = new PHPMailer(true);                            
    try {
        //Server settings
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';                      
        $mail->SMTPAuth = true;                             
        $mail->Username = $sender;     
        $mail->Password = 'lktrajmmihuskqvr';             
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );                         
        $mail->SMTPSecure = 'ssl';                           
        $mail->Port = 465;                                   

        //Send Email
        $mail->setFrom('delacruzjohnanthon@gmail.com', 'Windale Hardware Inc.');
        
        //Recipients
        $mail->addAddress($email);              
        $mail->addReplyTo('delacruzjohnanthon@gmail.com');
        
        //Content
        $mail->isHTML(true);                     

        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
		
       $_SESSION['result'] = 'Message has been sent';
	   
    } catch (Exception $e) {
	   $_SESSION['result'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
	   
    }
	
	
                if(isset($email, $subject, $message, $sender)){
                    $info = "We've sent a password reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;

                    header('location: ../onlinecustomer/reset-code.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
            
        }
        
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM tblcustomer WHERE ZIPCODE = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['CUSUNAME'];
            $_SESSION['CUSUNAME'] = $email;
            $info = "Please create a new password.";
            $_SESSION['info'] = $info;
            header('location: ../onlinecustomer/createnewpassword.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered an incorrect code!";
        }
    }

    //if user click change password button
    function validatePassword($password) {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one number.";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return "Password must contain at least one special character.";
        }
        return true;
    }
    
    if(isset($_POST['change-password'])) {
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        
        // First validate password strength
        $passwordValidation = validatePassword($password);
        if ($passwordValidation !== true) {
            $errors['password'] = $passwordValidation;
        }
        // Then check if passwords match
        else if($password !== $cpassword) {
            $errors['password'] = "Confirm password not matched!";
        }
        // If all validations pass, proceed with password update
        else {
            $code = 0;
            $email = $_SESSION['CUSUNAME']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            
            // Add error handling for SQL injection prevention
            $stmt = mysqli_prepare($con, "UPDATE tblcustomer SET ZIPCODE = ?, CUSPASS = ? WHERE CUSUNAME = ?");
            mysqli_stmt_bind_param($stmt, "iss", $code, $encpass, $email);
            $run_query = mysqli_stmt_execute($stmt);
            
            if($run_query) {
                $info = "Your password has been reset. You can now login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: ../onlinecustomer/backtologin');
                exit(); // Add exit after redirect
            } else {
                $errors['db-error'] = "Failed to change your password!";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
   //if login now button click
   // if(isset($_POST['login-now'])){
     //   header('Location: login.php');
    //}
?>