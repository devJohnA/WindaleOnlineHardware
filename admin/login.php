<?php
session_start();
$msg = "";

require_once("../include/initialize.php");

// Add reCAPTCHA secret key
define('RECAPTCHA_SECRET_KEY', 'YOUR_SECRET_KEY_HERE');
define('RECAPTCHA_SITE_KEY', 'YOUR_SITE_KEY_HERE');

// Function to verify reCAPTCHA
function verifyRecaptcha($recaptcha_response) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return json_decode($result);
}

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
    <form method="post" action="" role="login">
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
        // Handle form submission with reCAPTCHA
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'login'})
                    .then(function(token) {
                        document.getElementById('recaptchaResponse').value = token;
                        document.getElementById('loginForm').submit();
                    });
            });
        });
    </script>


   
    <?php 
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

if(isset($_POST['btnLogin'])){
    $username = trim($_POST['user_email']);
    $upass = trim($_POST['user_pass']);
    
    // Verify reCAPTCHA first
    $recaptcha_response = $_POST['recaptcha_response'];
    $recaptcha = verifyRecaptcha($recaptcha_response);
    
    if (!$recaptcha->success || $recaptcha->score < 0.5) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Security Check Failed',
                text: 'Please try again.',
            })
        </script>";
        exit();
    }
  
  if ($username == '' OR $upass == '') {
      echo "<script>
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Username and Password are required!',
          })
      </script>";
  } else {  
    $user = new User();

    if (User::checkUsernameExists($username)) {
        $res = User::userAuthentication($username, $upass);
          
          if ($res == true) { 
              echo "<script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Success!',
                      text: 'You login as ".$_SESSION['U_ROLE'].".',
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = '".($_SESSION['U_ROLE']=='Administrator' ? web_root."admin/index.php" : web_root."admin/login.php")."';
                      }
                  })
              </script>";
          } else {
              echo "<script>
                  Swal.fire({
                      title: 'Oops...',
                      html: '<div style=\"font-size: 3em;\">😢</div><p>Invalid Password. Please try again!</p>',
                  })
              </script>";
          }
      } else {
          echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Account does not exist. Please check your username.',
              })
          </script>";
      }
  }
} 
?>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
