<?php
// verify_recaptcha.php
header('Content-Type: application/json');

// Your reCAPTCHA secret key
$recaptcha_secret = "6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf";

// Get the token submitted with the form
$recaptcha_token = $_POST['recaptcha_token'] ?? '';

// Make request to reCAPTCHA API
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_token
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

// Check if the score is above your threshold (e.g., 0.5)
if ($result->success && $result->score >= 0.5) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'reCAPTCHA verification failed']);
}