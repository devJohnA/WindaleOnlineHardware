<?php
session_start();
header('Content-Type: application/json');
require_once("../include/initialize.php");

// Add reCAPTCHA keys
define('RECAPTCHA_SECRET_KEY', '6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf');
define('RECAPTCHA_SITE_KEY', '6Lcjy34qAAAAAD0k2NNynCgcbE6_W5Fy9GotDBZA');

// Function to verify reCAPTCHA
function verifyRecaptcha($recaptcha_response) {
    if (empty($recaptcha_response)) {
        return false;
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response);

    return $result && $result->success;
}

// Function to send JSON response
function sendJsonResponse($status, $message, $redirect = null) {
    $response = [
        'status' => $status,
        'message' => $message
    ];
    
    if ($redirect) {
        $response['redirect'] = $redirect;
    }
    
    echo json_encode($response);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Invalid request method');
}

// Handle login
if(isset($_POST['btnLogin'])){
    $email = trim($_POST['user_email']);
    $pass = trim($_POST['user_pass']);
    $recaptcha_response = $_POST['recaptcha_response'] ?? '';

    // Verify reCAPTCHA first
    if (!verifyRecaptcha($recaptcha_response)) {
        sendJsonResponse('error', 'reCAPTCHA verification failed');
    }
    
    if ($email == '' OR $pass == '') {
        sendJsonResponse('error', 'Email and Password are required!');
    }

    $user = new User();
    
    if (User::checkUsernameExists($email)) {
        $res = User::userAuthentication($email, $pass);
        if ($res == true) {
            $_SESSION['success_message'] = "Login successful!";
            sendJsonResponse('success', 'You logged in successfully!', web_root."admin/index.php");
        } else {
            sendJsonResponse('error', 'Invalid password. Please try again.');
        }
    } else {
        sendJsonResponse('error', 'Account not found. Please check your email address.');
    }
}

// If we get here, it means it's a POST request but btnLogin wasn't set
sendJsonResponse('error', 'Invalid form submission');
?>