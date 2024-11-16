<?php
session_start();
header('Content-Type: application/json'); // Set JSON response header
$msg = "";

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

if(isset($_SESSION['USERID'])){
    sendJsonResponse('success', 'Already logged in', web_root."admin/index.php");
}

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

// If it's not a POST request, return an error
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sendJsonResponse('error', 'Invalid request method');
}

if(isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

if(isset($_POST['btnLogin'])){
    $email = trim($_POST['user_email']);
    $pass = trim($_POST['user_pass']);
    $recaptcha_response = $_POST['recaptcha_response'] ?? '';

    // Verify reCAPTCHA first
    if (!verifyRecaptcha($recaptcha_response)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'reCAPTCHA Verification Failed',
                text: 'Please try again.',
            });
        </script>";
        return;
    }
    
    if ($email == '' OR $pass == '') {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email and Password are required!',
            });
        </script>";
        return;
    }

    $user = new User();
    
    if (User::checkUsernameExists($email)) {
        $res = User::userAuthentication($email, $pass);
        if ($res == true) {
            $_SESSION['success_message'] = "Login successful!";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'You logged in successfully!',
                }).then((result) => {
                    window.location.href = '".web_root."admin/index.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please try again.',
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Account Not Found',
                text: 'Please check your email address.',
            });
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Windale Hardware Store</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/windales.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
             <!-- Add reCAPTCHA v3 script -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY; ?>"></script>
            
    <style media="screen">
         *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #fff;
        }
        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }
        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child {
            background: linear-gradient(#1845ad, #23a2f6);
            left: -80px;
            top: -80px;
        }
        .shape:last-child {
            background: linear-gradient(to right, #fd2323, #f09819);
            right: -30px;
            bottom: -80px;
        }
        form {
            height: 480px;
            width: 350px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }
        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }
        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
            color: black;
        }
        .email {
            margin-top: 70px;
        }
        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgb(0 0 0 / 7%);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
            color: black;
        }
        ::placeholder {
            color: gray;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #fd2323;
            color: white;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .social {
            margin-top: 20px;
            text-align: center;
        }
        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }
        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }
        .p {
            color: gray;
            cursor: pointer;
        }
        .logo-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .logo-container img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
    </div>
    <form method="POST" id="loginForm">
    <div class="logo-container">
            <img src="win.png" alt="Windale Hardware Store Logo">
        </div>
        <?php echo $msg; ?>
<div class="inputgroup">
        <label for="email" >Email</label>
        <input type="email" name="user_email" placeholder="Email">

        <label for="password">Password</label>
        <input type="password" name="user_pass" id="password" placeholder="Password">
      </div>
        <p class="text-end" style="float:right;"><a href="choose-forgotpass.php" class="p">Forgot Password?</p>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <button type="submit" name="btnLogin">Log In</button>
        <div class="social">
        <a href="../index.php" class="text-dark text-end text-decoration-none">Back to Home Page</a>
        </div>
    </form>
    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    grecaptcha.ready(function() {
        grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'login'})
            .then(function(token) {
                document.getElementById('recaptchaResponse').value = token;
                submitForm();
            })
            .catch(function(error) {
                console.error('reCAPTCHA error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to verify reCAPTCHA. Please try again.',
                });
            });
    });
});

function submitForm() {
    const formData = new FormData(document.getElementById('loginForm'));
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'An unexpected error occurred. Please try again.',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
        });
    });
}
    </script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
