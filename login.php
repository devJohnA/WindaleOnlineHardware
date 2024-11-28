

<?php

function setSecurityHeaders() {
    // Prevent clickjacking attacks
    header("X-Frame-Options: SAMEORIGIN");
  
    // Enable the browser's built-in XSS protection
    header("X-XSS-Protection: 1; mode=block");
  
    // Prevent MIME type sniffing
    header("X-Content-Type-Options: nosniff");
  
    // Enforce HTTPS
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
  
    // Control which resources the browser is allowed to load
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' https://cdnjs.cloudflare.com 'unsafe-inline'; img-src 'self' data:; font-src 'self'; frame-src 'none'; object-src 'none';");
  
    // Control how much referrer information should be included with requests
    header("Referrer-Policy: strict-origin-when-cross-origin");
  
    // Disable certain browser features and APIs
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
  }
  
  // Call this function at the beginning of your PHP files
  setSecurityHeaders();
  
  
function checkRequest() {
    $suspicious_inputs = array('UNION', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'EXEC', 'SCRIPT');
    $request = $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . ' ' . file_get_contents('php://input');
    
    foreach ($suspicious_inputs as $input) {
        if (stripos($request, $input) !== false) {
            error_log("Suspicious request blocked: " . $request);
            die("Access denied");
        }
    }
    
    // Check for unusual number of parameters
    if (count($_GET) + count($_POST) > 20) {
        error_log("Request with too many parameters blocked: " . $request);
        die("Access denied");
    }
}

// Function for rate limiting
function rateLimit($key, $limit = 5, $period = 60) {
    $file = sys_get_temp_dir() . '/rate_limit_' . md5($key);
    
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if ($data['time'] + $period > time()) {
            if ($data['count'] >= $limit) {
                http_response_code(429);
                die("Rate limit exceeded. Try again later.");
            }
            $data['count']++;
        } else {
            $data = ['count' => 1, 'time' => time()];
        }
    } else {
        $data = ['count' => 1, 'time' => time()];
    }
    
    file_put_contents($file, json_stringify($data));
}


require_once ("include/initialize.php"); 

if (@$_GET['page'] <= 2 or @$_GET['page'] > 5) {

 # code...

   // unset($_SESSION['PRODUCTID']);

   // // unset($_SESSION['QTY']);

   // // unset($_SESSION['TOTAL']);

} 







if(isset($_POST['sidebarLogin'])){

 $email = trim($_POST['U_USERNAME']);

 $upass  = trim($_POST['U_PASS']);

 $h_upass = sha1($upass);

 

  if ($email == '' OR $upass == '') {



     message("Invalid Username and Password!", "error");

     redirect(web_root."index.php");

        

   } else {   

       $cus = new Customer();

       $cusres = $cus::cusAuthentication($email,$h_upass);



       if ($cusres==true){





        header('Location:'.$_SERVER['HTTP_REFERER']);

       }else{

            message("Invalid Username and Password! Please contact administrator", "error");

            redirect(web_root."index.php");

       }



}

}



define('RECAPTCHA_SECRET_KEY', '6Lcjy34qAAAAAB9taC5YJlHQoWOzO93xScnYI2Lf');
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 15); // Minutes

session_start();

// Function to verify reCAPTCHA response
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

// Function to check for SQL injection in user input
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

// Function to log SQL injection attempts (for security purposes)
function logSqlInjectionAttempt($ip_address) {
    global $mydb;
    $mydb->setQuery("INSERT INTO `tbl_sql_injection_logs` (`ip_address`, `attempt_date`) VALUES ('" . $mydb->escape_value($ip_address) . "', NOW())");
    $mydb->executeQuery();
}

// Function to check the login attempts for the email (from session)
function checkLoginAttempts($email) {
    // Check if there are attempts stored in the session
    if (isset($_SESSION['login_attempts'][$email])) {
        $attempts = $_SESSION['login_attempts'][$email]['attempts'];
        $last_attempt = $_SESSION['login_attempts'][$email]['last_attempt'];

        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $lockout_time = strtotime($last_attempt) + (LOCKOUT_TIME * 60);
            if (time() < $lockout_time) {
                // User is locked out, calculate remaining time
                $remaining_time = ceil(($lockout_time - time()) / 60);
                return array(
                    'locked' => true,
                    'remaining_time' => $remaining_time
                );
            } else {
                // Reset attempts after lockout period has passed
                $_SESSION['login_attempts'][$email]['attempts'] = 0;
                $_SESSION['login_attempts'][$email]['last_attempt'] = null;
            }
        }
    }

    return array('locked' => false);
}

// Function to increment login attempts in session
function incrementLoginAttempts($email) {
    if (!isset($_SESSION['login_attempts'][$email])) {
        $_SESSION['login_attempts'][$email] = array(
            'attempts' => 0,
            'last_attempt' => null
        );
    }

    $_SESSION['login_attempts'][$email]['attempts']++;
    $_SESSION['login_attempts'][$email]['last_attempt'] = date('Y-m-d H:i:s');
}

// Function to reset login attempts
function resetLoginAttempts($email) {
    unset($_SESSION['login_attempts'][$email]);
}

header('Content-Type: application/json');

if(isset($_POST['modalLogin'])) {
    $email = trim($_POST['U_USERNAME']);
    $upass = trim($_POST['U_PASS']);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA first
    $recaptcha_verify = verifyRecaptcha($recaptcha_response);
    if (!$recaptcha_verify->success) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please complete the reCAPTCHA verification.'
        ]);
        exit;
    }

    // Check for SQL Injection
    if (containsSqlInjection($email) || containsSqlInjection($upass)) {
        logSqlInjectionAttempt($ip_address);
        echo json_encode([
            'status' => 'error',
            'message' => 'Security Breach Detected',
            'sqlInjection' => true
        ]);
        exit;
    }

    // Validate email and password fields
    if ($email == '' OR $upass == '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Username and Password!'
        ]);
        exit;
    }

    // Check if account is locked due to too many failed login attempts
    $attempt_check = checkLoginAttempts($email);
    if ($attempt_check['locked']) {
        echo json_encode([
            'status' => 'error',
            'message' => "Your account is temporarily locked. Please try again in " . $attempt_check['remaining_time'] . " minutes."
        ]);
        exit;
    }

    // Query the database for the user
    $mydb->setQuery("SELECT * FROM `tblcustomer` WHERE `CUSUNAME` = '" . $mydb->escape_value($email) . "'");
    $cur = $mydb->executeQuery();

    if ($mydb->num_rows($cur) > 0) {
        $customer_data = $mydb->loadSingleResult();

        if (password_verify($upass, $customer_data->CUSPASS)) {
            // Reset login attempts on successful login
            resetLoginAttempts($email);

            $_SESSION['CUSID'] = $customer_data->CUSTOMERID;
            $_SESSION['CUSNAME'] = $customer_data->FNAME . ' ' . $customer_data->LNAME;

            if (empty($_POST['proid'])) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'redirect' => web_root . "index.php"
                ]);
            } else {
                $proid = $_POST['proid'];
                $cusid = $_SESSION['CUSID'];
                $mydb->setQuery("INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`) VALUES ('$proid', '$cusid', NOW(), 0)");
                $mydb->executeQuery();

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'redirect' => web_root . "index.php?q=profile"
                ]);
            }
        } else {
            // Increment failed login attempts
            incrementLoginAttempts($email);
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid Username and Password!'
            ]);
        }
    } else {
        // Email doesn't exist in the database, so just increment attempts
        incrementLoginAttempts($email);
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Username and Password!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
?>