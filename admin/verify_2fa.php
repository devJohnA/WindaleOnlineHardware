<?php
require_once '../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "dried");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    // Basic validation
    if (empty($code) || strlen($code) !== 6 || !ctype_digit($code)) {
        echo json_encode(['success' => false, 'message' => 'Invalid code']);
        exit;
    }

    // Get admin user with the secret key
    $stmt = $conn->prepare("SELECT USERID, SECRET_KEY FROM tbluseraccount WHERE U_ROLE = 'Administrator'");
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!$admin || empty($admin['SECRET_KEY'])) {
        echo json_encode(['success' => false, 'message' => '2FA not setup for admin']);
        exit;
    }

    // Verify code
    $ga = new GoogleAuthenticator();
    if ($ga->checkCode($admin['SECRET_KEY'], $code, 2)) {
        // Log success
        $conn->query("INSERT INTO login_logs (USERID, login_time, status) VALUES ({$admin['USERID']}, NOW(), 'success')");
        
        // Redirect to login form
        echo json_encode(['success' => true, 'redirect' => 'login.php']);
    } else {
        // Log failure
        $conn->query("INSERT INTO login_logs (USERID, login_time, status) VALUES ({$admin['USERID']}, NOW(), 'failed_2fa')");
        echo json_encode(['success' => false, 'message' => 'Invalid code']);
    }

    $stmt->close();
    $conn->close();
}
?>