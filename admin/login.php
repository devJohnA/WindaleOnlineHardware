<?php

// Function to set security headers
function setSecurityHeaders() {
  header("X-Frame-Options: SAMEORIGIN");
  header("X-XSS-Protection: 1; mode=block");
  header("X-Content-Type-Options: nosniff");
  header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
  header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' https://cdnjs.cloudflare.com 'unsafe-inline'; img-src 'self' data:; font-src 'self'; frame-src 'none'; object-src 'none';");
  header("Referrer-Policy: strict-origin-when-cross-origin");
  header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}

// Call the function to set headers
setSecurityHeaders();
   

require_once("../include/initialize.php");



 ?>


<?php

 // login confirmation

  if(isset($_SESSION['USERID'])){

    redirect(web_root."admin/index.php");

  }

  ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../img/windales.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>.login-dark {
  height: 600px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-dark form {
  width: 320px;
  padding: 40px;
  border-radius: 4px;
  color: black;
}

.login-dark .illustration {
  text-align: center;
  padding: 15px 0 20px;
}

.login-dark form .form-control {
  background: none;
  border: none;
  border-bottom: 1px solid #434a52;
  border-radius: 0;
  box-shadow: none;
  outline: none;
  color: inherit;
}

.login-dark form .btn-primary {
  background: #fd2323;
  border: none;
  border-radius: 4px;
  padding: 11px;
  box-shadow: none;
  margin-top: 26px;
  text-shadow: none;
  outline: none;
  width: 100%;
}

.login-dark form .btn-primary:hover, 
.login-dark form .btn-primary:active {
  background: seagreen;
  outline: none;
}

.login-dark form .forgot {
  display: block;
  text-align: center;
  font-size: 12px;
  color: #6f7a85;
  opacity: 0.9;
  text-decoration: none;
}

.login-dark form .forgot:hover, 
.login-dark form .forgot:active {
  opacity: 1;
  text-decoration: none;
}

.login-dark form .btn-primary:active {
  transform: translateY(1px);
}

.p {
  color: gray;
  cursor:pointer;
}

.custom-shape-divider-bottom-1728231997 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.custom-shape-divider-bottom-1728231997 svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 110px;
}

.custom-shape-divider-bottom-1728231997 .shape-fill {
    fill: #FD2323;
}

@keyframes logoAnimation {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.login-dark .illustration img {
  animation: logoAnimation 2s ease-in-out infinite;
}
              </style>
</head>

<body style="background-color:hsl(49 26.8% 92% /1);">
<div class="custom-shape-divider-bottom-1728231997">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z" class="shape-fill"></path>
    </svg>
</div>
            <div class="login-dark" style="height: 600px;">
            <form method="post" action="" role="login">
                    <h2 class="sr-only">Login Form</h2>
                    <div class="illustration"><img src="win.png" width="120" height="150"></i></div>
                    <div class="form-group"><input class="form-control" type="email" name="user_email" placeholder="Email"></div>
                    <div class="form-group"><input class="form-control" type="password" name="user_pass" id="password" placeholder="Password"></div>
                    <p class="text-end" style="float:right;"><a href="forgot-password.php"  class="p">Forgot Password?</p>
                    <div class="form-group"><button type="submit" name="btnLogin" class="btn btn-primary btn-block">Log In</button></div>
                   <p class="text-center mt-1">
            <a href="../index.php" class="text-dark text-decoration-none">Back to Homasde Page</a>
        </p></form>
            </div>
           
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
  
  if (containsSqlInjection($username) || containsSqlInjection($upass)) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Security Breach Detected',
        html: '<div style=\"font-size: 1.2em;\">🚨 WARNING: SQL Injection Attempt Detected 🚨</div><br>' +
              '<p>Your IP address has been logged and reported to cybersecurity authorities.</p>' +
              '<p>Further attempts will result in immediate account lockout and potential legal action.</p>' +
              '<br><div style=\"font-size: 1.1em; color: #ff0000;\">This incident will be investigated thoroughly.</div>',
        confirmButtonText: 'I Understand the Consequences',
        confirmButtonColor: '#d33',
        showCancelButton: false,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php';
        }
    });
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
      $h_upass = sha1($upass);
      
      if (User::checkUsernameExists($username)) {
          $res = User::userAuthentication($username, $h_upass);
          
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