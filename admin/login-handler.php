<?php
session_start();
require_once("../include/initialize.php");

define('RECAPTCHA_SECRET_KEY', '6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf');

function verifyRecaptcha($recaptcha_response) {
    if (empty($recaptcha_response)) {
        return false;
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response,
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response);

    return $result && $result->success;
}

if (isset($_POST['btnLogin'])) {
    $email = trim($_POST['user_email']);
    $pass = trim($_POST['user_pass']);
    $recaptcha_response = $_POST['recaptcha_response'] ?? '';

    // Verify reCAPTCHA
    if (!verifyRecaptcha($recaptcha_response)) {
        echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed.']);
        exit;
    }

    if ($email == '' || $pass == '') {
        echo json_encode(['status' => 'error', 'message' => 'Email and Password are required.']);
        exit;
    }

    $user = new User();
    if (User::checkUsernameExists($email)) {
        $res = User::userAuthentication($email, $pass);
        if ($res) {
            $_SESSION['success_message'] = "Login successful!";
            echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'redirect' => web_root . "admin/index.php"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Account not found. Please check your email address.']);
    }
    exit;
}
?>
