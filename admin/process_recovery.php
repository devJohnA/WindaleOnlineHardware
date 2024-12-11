<?php
// process_recovery.php
session_start();
require_once 'dbcon/conn.php';
require_once '../vendor/autoload.php';
require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

try {
    // First, verify if GoogleAuthenticator class exists
    if (!class_exists('Sonata\GoogleAuthenticator\GoogleAuthenticator')) {
        throw new Exception("GoogleAuthenticator class not found. Please check if the package is installed.");
    }

    // Check database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Check if user exists
    $query = "SELECT USERID, U_NAME, U_USERNAME FROM tbluseraccount WHERE U_USERNAME = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Database execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Email not found in our records']);
        exit;
    }

    // Generate new secret key
    $ga = new GoogleAuthenticator();
    $newSecretKey = $ga->generateSecret();

    error_log("Generated new secret key: " . $newSecretKey);

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    // Debug SMTP connection
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP Debug: $str");
    };

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'delacruzjohnanthon@gmail.com';
    $mail->Password = 'mdbdvmnypposvdtk'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('delacruzjohnanthon@gmail.com', 'Windale Hardware System');
    $mail->addAddress($user['U_USERNAME'], $user['U_NAME']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your New 2FA Authentication Details';

    // Simplified email template with only the secret key
    $emailBody = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2 style='color: #2c3e50; text-align: center;'>2FA Recovery Information</h2>
            
            <p>Dear {$user['U_NAME']},</p>
            
            <p>Your new 2FA secret key is: <strong>{$newSecretKey}</strong></p>
            
            <p>Please enter this key in your authenticator app to continue using 2FA.</p>
            
            <p>For security reasons, please do not share this key with anyone.</p>
        </div>
    </body>
    </html>";

    $mail->Body = $emailBody;
    $mail->AltBody = "Your new secret key: {$newSecretKey}";

    if (!$mail->send()) {
        throw new Exception("Email sending failed: " . $mail->ErrorInfo);
    }

    error_log("Email sent successfully");

    // Update user's secret key in database
    $updateQuery = "UPDATE tbluseraccount SET SECRET_KEY = ? WHERE USERID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    if (!$updateStmt) {
        throw new Exception("Database prepare failed for update: " . $conn->error);
    }
    
    $updateStmt->bind_param("si", $newSecretKey, $user['USERID']);
    if (!$updateStmt->execute()) {
        throw new Exception("Database update failed: " . $updateStmt->error);
    }

    error_log("Database updated successfully");

    echo json_encode([
        'success' => true,
        'message' => 'Recovery information has been sent to your email. Please check your inbox.'
    ]);

} catch (Exception $e) {
    error_log("Detailed error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    if (isset($conn)) $conn->close();
}
?>