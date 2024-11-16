<?php
session_start();
require_once("../include/initialize.php");

define('RECAPTCHA_SECRET_KEY', '6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf'); // Use your secret key here

function verifyRecaptcha($recaptcha_response) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    return $captcha_success;
}

function containsSqlInjection($str) {
    // Allow common email characters
    $str = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '', $str);
    
    $sql_regexes = array(
        "/(\s|'|\"|=|<|>|\(|\)|\{|\}|;|--|\^|\/\*|\*\/|!\d+|_|\%|\\\\)/i",
        "/(union\s+select|select\s+from|insert\s+into|delete\s+from|update\s+set|drop\s+table)/i"
    );
    
    foreach ($sql_regexes as $regex) {
        if (preg_match($regex, $str)) {
            return true;
        }
    }
    return false;
}

header('Content-Type: application/json');

$username = trim($_POST['user_email']);
$upass = trim($_POST['user_pass']);
$recaptcha_response = $_POST['recaptcha_response'];

// Verify reCAPTCHA first
$recaptcha_verify = verifyRecaptcha($recaptcha_response);
if (!$recaptcha_verify->success || $recaptcha_verify->score < 0.5) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Security verification failed. Please try again.'
    ]);
    exit;
}

// Check for SQL injection attempts
if (containsSqlInjection($username) || containsSqlInjection($upass)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Security breach detected',
        'sqlInjection' => true
    ]);
    exit;
}

if ($username == '' OR $upass == '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username and Password are required!'
    ]);
    exit;
}

if (User::checkUsernameExists($username)) {
    $res = User::userAuthentication($username, $upass);
    
    if ($res == true) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
            'redirect' => web_root . "admin/index.php"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Password. Please try again!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Account does not exist. Please check your username.'
    ]);
}
?>