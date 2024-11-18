<?php
session_start();

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if token exists and is valid
if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);
    
    if (
        isset($_SESSION['verification_token']) &&
        isset($_SESSION['token_expiry']) &&
        isset($_SESSION['verification_email']) &&
        $_SESSION['verification_token'] === $token &&
        time() <= $_SESSION['token_expiry']
    ) {
        // Token is valid - store verified email and clear verification data
        $_SESSION['verified_email'] = $_SESSION['verification_email'];
        $_SESSION['success'] = 'Email verified successfully!';
        
        // Clear verification data
        unset($_SESSION['verification_token']);
        unset($_SESSION['token_expiry']);
        unset($_SESSION['verification_email']);
        
        // Redirect to authentication page
        header('Location: authenticator.php');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid or expired verification link. Please try again.';
        header('Location: index.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid verification link.';
    header('Location: index.php');
    exit;
}
?>