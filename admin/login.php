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
    <!-- Your existing CSS styles here -->
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