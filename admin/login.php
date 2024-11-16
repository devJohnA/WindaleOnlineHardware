<?php
session_start();
$msg = "";
require_once("../include/initialize.php");

// reCAPTCHA configuration
$recaptcha_site_key = '6Lcjy34qAAAAAD0k2NNynCgcbE6_W6Fy9GotDBZA'; // Use your site key here

if(isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

if (isset($_SESSION['success_message'])) {
    $msg = "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']);
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
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha_site_key; ?>"></script>
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
    <form id="loginForm" method="post" action="" role="login">
        <div class="logo-container">
            <img src="win.png" alt="Windale Hardware Store Logo">
        </div>
        <?php echo $msg; ?>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        
        <div class="inputgroup">
            <label for="email">Email</label>
            <input type="email" name="user_email" placeholder="Email">

            <label for="password">Password</label>
            <input type="password" name="user_pass" id="password" placeholder="Password">
        </div>
        <p class="text-end" style="float:right;"><a href="choose-forgotpass.php" class="p">Forgot Password?</p>
        <button type="submit" name="btnLogin">Log In</button>
        <div class="social">
            <a href="../index.php" class="text-dark text-end text-decoration-none">Back to Home Page</a>
        </div>
    </form>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Execute reCAPTCHA verification
        grecaptcha.execute('<?php echo $recaptcha_site_key; ?>', {action: 'login'})
        .then(function(token) {
            document.getElementById('recaptchaResponse').value = token;
            
            const formData = new FormData(e.target);
            
            // Add the form data to URL-encoded format
            const urlEncodedData = new URLSearchParams(formData).toString();
            
            fetch('verify_admin_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: urlEncodedData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else if (data.sqlInjection) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Security Breach Detected',
                        html: '<div style="font-size: 1.2em;">🚨 WARNING: SQL Injection Attempt Detected 🚨</div><br>' +
                              '<p>Your IP address has been logged and reported to cybersecurity authorities.</p>' +
                              '<p>Further attempts will result in immediate account lockout and potential legal action.</p>' +
                              '<br><div style="font-size: 1.1em; color: #ff0000;">This incident will be investigated thoroughly.</div>',
                        confirmButtonText: 'I Understand the Consequences',
                        confirmButtonColor: '#d33',
                        showCancelButton: false,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            });
        });
    });
    </script>
</body>
</html>