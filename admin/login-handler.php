<?php
session_start();
header('Content-Type: application/json');
require_once("../include/initialize.php");

class LoginHandler {
    private const RECAPTCHA_SECRET_KEY = '6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf';
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutes in seconds
    
    private function verifyRecaptcha($recaptchaResponse) {
        if (empty($recaptchaResponse)) {
            return false;
        }
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => self::RECAPTCHA_SECRET_KEY,
            'response' => $recaptchaResponse
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return false;
        }
        
        $result = json_decode($response);
        return $result && $result->success;
    }
    
    private function isAccountLocked($email) {
        $attempts = $_SESSION['login_attempts'][$email] ?? 0;
        $lastAttempt = $_SESSION['last_attempt'][$email] ?? 0;
        
        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $timeElapsed = time() - $lastAttempt;
            if ($timeElapsed < self::LOCKOUT_TIME) {
                return true;
            }
            // Reset attempts after lockout period
            $this->resetLoginAttempts($email);
        }
        return false;
    }
    
    private function incrementLoginAttempts($email) {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }
        if (!isset($_SESSION['last_attempt'])) {
            $_SESSION['last_attempt'] = [];
        }
        
        $_SESSION['login_attempts'][$email] = ($_SESSION['login_attempts'][$email] ?? 0) + 1;
        $_SESSION['last_attempt'][$email] = time();
    }
    
    private function resetLoginAttempts($email) {
        $_SESSION['login_attempts'][$email] = 0;
        $_SESSION['last_attempt'][$email] = 0;
    }
    
    private function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }
    
    public function handleLogin() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            if (!isset($_POST['btnLogin'])) {
                throw new Exception('Invalid form submission');
            }
            
            $email = $this->sanitizeInput($_POST['user_email'] ?? '');
            $pass = $_POST['user_pass'] ?? '';
            $recaptchaResponse = $_POST['recaptcha_response'] ?? '';
            
            if (empty($email) || empty($pass)) {
                throw new Exception('Email and Password are required!');
            }
            
            if ($this->isAccountLocked($email)) {
                $remainingTime = self::LOCKOUT_TIME - (time() - $_SESSION['last_attempt'][$email]);
                throw new Exception(sprintf(
                    'Account temporarily locked. Please try again in %d minutes.',
                    ceil($remainingTime / 60)
                ));
            }
            
            if (!$this->verifyRecaptcha($recaptchaResponse)) {
                throw new Exception('reCAPTCHA verification failed');
            }
            
            if (!User::checkUsernameExists($email)) {
                throw new Exception('Account not found. Please check your email address.');
            }
            
            if (!User::userAuthentication($email, $pass)) {
                $this->incrementLoginAttempts($email);
                throw new Exception('Invalid password. Please try again.');
            }
            
            // Success - reset attempts and set session
            $this->resetLoginAttempts($email);
            $_SESSION['success_message'] = "Login successful!";
            
            return [
                'status' => 'success',
                'message' => 'You logged in successfully!',
                'redirect' => web_root . "admin/index.php"
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

// Handle the login request
$loginHandler = new LoginHandler();
echo json_encode($loginHandler->handleLogin());