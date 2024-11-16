

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

function logSqlInjectionAttempt($ip_address) {
    global $mydb;
    $mydb->setQuery("INSERT INTO `tbl_sql_injection_logs` (`ip_address`, `attempt_date`) VALUES ('" . $mydb->escape_value($ip_address) . "', NOW())");
    $mydb->executeQuery();
}


header('Content-Type: application/json');

if(isset($_POST['modalLogin'])) {
    $email = trim($_POST['U_USERNAME']);
    $upass = trim($_POST['U_PASS']);
    $ip_address = $_SERVER['REMOTE_ADDR'];
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

    if (containsSqlInjection($email) || containsSqlInjection($upass)) {
        logSqlInjectionAttempt($ip_address);
        echo json_encode([
            'status' => 'error',
            'message' => 'Security Breach Detected',
            'sqlInjection' => true
        ]);
        exit;
    }
   
   if ($email == '' OR $upass == '') {
       echo json_encode([
           'status' => 'error',
           'message' => 'Invalid Username and Password!'
       ]);
       exit;
   } else {
       $h_upass = sha1($upass);
       
       global $mydb;
       $mydb->setQuery("SELECT * FROM `tblcustomer` WHERE `CUSUNAME` = '" . $mydb->escape_value($email) . "' AND `CUSPASS` = '" . $mydb->escape_value($h_upass) . "'");
       $cur = $mydb->executeQuery();
       
       if($mydb->num_rows($cur) > 0) {
           $customer_data = $mydb->loadSingleResult();
           
           if (!empty($customer_data->code)) {
               echo json_encode([
                   'status' => 'error',
                   'message' => 'Please verify your email address first.'
               ]);
               exit;
           }
           
           $_SESSION['CUSID'] = $customer_data->CUSTOMERID;
           $_SESSION['CUSNAME'] = $customer_data->FNAME . ' ' . $customer_data->LNAME;
           
           if(empty($_POST['proid'])) {
               echo json_encode([
                   'status' => 'success',
                   'message' => 'Login successful!',
                   'redirect' => web_root . "index.php"
               ]);
           } else {
               $proid = $_POST['proid'];
               $cusid = $_SESSION['CUSID'];
               $mydb->setQuery("INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`)
                                VALUES ('$proid', '$cusid', NOW(), 0)");
               $mydb->executeQuery();
               
               echo json_encode([
                   'status' => 'success',
                   'message' => 'Login successful!',
                   'redirect' => web_root . "index.php?q=profile"
               ]);
           }
       } else {
           echo json_encode([
               'status' => 'error',
               'message' => 'Invalid Username and Password!'
           ]);
       }
   }
} else {
   echo json_encode([
       'status' => 'error',
       'message' => 'Invalid request'
   ]);
}
?>