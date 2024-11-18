<?php
session_start();

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);

    // Database connection settings
require_once 'dbcon/conn.php';
    // Retrieve the token from the database
    $stmt = $conn->prepare("SELECT email, expiry FROM email_verifications WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email, $expiry);
    $stmt->fetch();
    $stmt->close();

    if (!$email) {
        // Token not found
        $_SESSION['error'] = 'Invalid verification link.';
        header('Location: index');
        exit;
    }

    if ($expiry < time()) {
        // Token expired
        $_SESSION['error'] = 'The verification link has expired. Please request a new one.';
        header('Location: index');
        exit;
    }

    // Token is valid, store email in session
    $_SESSION['verified_email'] = $email;
    $_SESSION['success'] = 'Email verified successfully!';
    
    // Optionally, you can mark the token as used or expired in the database here
    // $stmt = $conn->prepare("DELETE FROM email_verifications WHERE token = ?");
    // $stmt->bind_param("s", $token);
    // $stmt->execute();

    $conn->close();

    // Redirect to authenticator page
    header('Location: authenticator');
    exit;
} else {
    $_SESSION['error'] = 'Invalid verification link.';
    header('Location: index');
    exit;
}
?>
