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
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            grecaptcha.ready(function () {
                grecaptcha.execute('6Lcjy34qAAAAAD0k2NNynCgcbE6_W5Fy9GotDBZA', { action: 'login' })
                    .then(function (token) {
                        document.getElementById('recaptchaResponse').value = token;

                        // Submit form via fetch
                        const formData = new FormData(document.getElementById('loginForm'));

                        fetch('login-handler.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
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
                                        text: data.message || 'An unexpected error occurred.',
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'A network error occurred. Please try again later.',
                                });
                            });
                    });
            });
        });
    </script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
