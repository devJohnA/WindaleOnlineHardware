<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require("../config.php");
require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';
?>
<?php 
session_start();

$email = "";
$name = "";
$errors = array();


//connect to database
$con = mysqli_connect('localhost', 'u510162695_dried', '1Dried_password', 'u510162695_dried');

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM tbluseraccount WHERE U_USERNAME='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(100000, 999999);
            $insert_code = "UPDATE tbluseraccount SET Code = $code WHERE U_USERNAME = '$email'";
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
        $mail->Password = 'evbivfyxreypzntu';             
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
        $mail->setFrom('delacruzjohnanthon@gmail.com', 'Windale Hardware Inc');
        
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

                    header('location: reset-code');
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
        $check_code = "SELECT * FROM tbluseraccount WHERE Code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['U_USERNAME'];
            $_SESSION['U_USERNAME'] = $email;
            $info = "Please create a new password.";
            $_SESSION['info'] = $info;
            header('location: createnewpassword');
            exit();
        }else{
            $errors['otp-error'] = "You've entered an incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['U_USERNAME']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE tbluseraccount SET Code = $code, U_PASS = '$encpass' WHERE U_USERNAME = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password has been reset. You can now login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: backtologin');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
   // if(isset($_POST['login-now'])){
     //   header('Location: login.php');
    //}
?>