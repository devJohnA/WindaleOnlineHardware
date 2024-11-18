<?php 
// verify_2fa.php
require_once '../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set proper headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Database connection
try {
    $conn = new mysqli("localhost", "u510162695_dried", "1Dried_password", "u510162695_dried");
    
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        
        // Validate code format
        if (empty($code) || strlen($code) !== 6 || !ctype_digit($code)) {
            throw new Exception('Invalid code format');
        }
        
        // Get users with their secret keys
        $stmt = $conn->prepare("SELECT USERID, SECRET_KEY, U_ROLE FROM tbluseraccount WHERE U_ROLE IN ('Administrator', 'Staff') AND SECRET_KEY IS NOT NULL");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement');
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $validAuth = false;
        $ga = new GoogleAuthenticator();
        
        // Check the code against all admin/staff secret keys
        while ($user = $result->fetch_assoc()) {
            if ($ga->checkCode($user['SECRET_KEY'], $code, 2)) { // 2 = 2*30sec clock tolerance
                // Start session and set necessary session variables
                session_start();
                $_SESSION['2fa_verified'] = true;
                $_SESSION['admin_id'] = $user['USERID'];
                $_SESSION['user_role'] = $user['U_ROLE'];
                
                $validAuth = true;
                
                echo json_encode([
                    'success' => true,
                    'redirect' => 'login.php'
                ]);
                break;
            }
        }
        
        if (!$validAuth) {
            throw new Exception('Invalid authentication code');
        }
        
    } else {
        throw new Exception('Invalid request method');
    }
    
} catch (Exception $e) {
    error_log('2FA Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}