<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
header('Content-Type: application/json');
session_start();

try {
    // Validate email
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email || !preg_match('/@gmail\.com$/i', $email)) {
        throw new Exception('Please enter a valid Gmail address.');
    }

    // Database connection settings
    $dbHost = "localhost";
    $dbUsername = "u510162695_dried";
    $dbPassword = "1Dried_password";
    $dbName = "u510162695_dried";

    // Create database connection
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

      // Check if email exists in tbluseraccount
      $stmt = $conn->prepare("SELECT USERID FROM tbluseraccount WHERE U_USERNAME = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows === 0) {
          // Email does not exist in the database
          $stmt->close();
          $conn->close();
          echo json_encode([
              'success' => false,
              'message' => 'This email is not registered in our system.'
          ]);
          exit;
      }
  
      // Generate verification token
      $token = bin2hex(random_bytes(32));
      $token_expiry = time() + 3600; // Token expires in 1 hour
  
      // Store the token in the database (if you have a table for this purpose)
      // If the user already has a pending token, update the existing one
  
      // Assuming a table 'email_verifications' with columns 'email', 'token', 'expiry'
      $stmt = $conn->prepare("REPLACE INTO email_verifications (email, token, expiry) VALUES (?, ?, ?)");
      $stmt->bind_param("ssi", $email, $token, $token_expiry);
      $stmt->execute();
      $stmt->close();
  
      // Create verification link
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
      $verification_link = $actual_link . "/admin/verify?token=" . $token;


    // Create email HTML content
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .button {
                background-color: #4285f4;
                color: white !important;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 4px;
                display: inline-block;
                margin: 20px 0;
                font-weight: bold;
            }
            .footer {
                margin-top: 30px;
                font-size: 12px;
                color: #666666;
                border-top: 1px solid #eeeeee;
                padding-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Email Verification</h2>
            <p>Hello,</p>
            <p>Thank you for starting the authentication process. To proceed, please click the button below:</p>
            
            <a href='{$verification_link}' class='button'>Start Authentication</a>
            
            <p>If the button above doesn't work, copy and paste this link into your browser:</p>
            <p>{$verification_link}</p>
            
            <div class='footer'>
                <p>This verification link will expire in 1 hour for security reasons.</p>
                <p>If you didn't request this verification, please ignore this email.</p>
            </div>
        </div>
    </body>
    </html>";

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'delacruzjohnanthon@gmail.com';
    $mail->Password   = 'ialwoqltexquffhc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('delacruzjohnanthon@gmail.com', 'Windale Hardware');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your Email Address';
    $mail->Body    = $emailBody;
    $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $emailBody));

    // Send email
    $mail->send();

    
    $_SESSION['email_verified'] = true;
    $_SESSION['verified_email'] = $email;
    
    echo json_encode([
        'success' => true,
        'message' => 'Verification email sent successfully! Please check your inbox.'
    ]);

} catch (Exception $e) {
    error_log("Email sending failed: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send verification email. Please try again.'
    ]);
}
?>