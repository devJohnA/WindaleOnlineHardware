<?php
session_start();

// Prevent direct access to authenticator without email verification
// if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
//     header('Location: verification');
//     exit;
// }

// Prevent accessing authenticator if already 2FA verified
if (isset($_SESSION['2fa_verified']) && $_SESSION['2fa_verified'] === true) {
    header('Location: login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Authenticator Session</title>
    <meta name="description" content="Elisyam is a Web App and Admin Dashboard Template built with Bootstrap 4">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script>
      WebFont.load({
        google: {"families":["Montserrat:400,500,600,700","Noto+Sans:400,700"]},
        active: function() {
            sessionStorage.fonts = true;
        }
      });
    </script>
    <link rel="icon" href="../img/windales.png">
    <link rel="stylesheet" href="../pagesstyle/assets/vendors/css/base/bootstrap.min.css">
    <link rel="stylesheet" href="../pagesstyle/assets/vendors/css/base/elisyam-1.5.min.css">
    <style>
        .loader {
            display: none;
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-fixed-01">
    <div class="container-fluid h-100 overflow-y">
        <div class="row flex-row h-100">
            <div class="col-12 my-auto">
                <div class="lock-form mx-auto">
                    <div class="photo-profil">
                        <div class="icon"><i class="la la-unlock"></i></div>
                        <img src="../img/Authenticator.png" alt="..." class="img-fluid rounded-circle">
                    </div>
                    <h3>Google Authenticator</h3>

                    <!-- Display error or success messages -->
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Verification Form -->
                    <form id="verifyForm">
                        <div class="group material-input">
                            <input type="text" 
                                   class="form-control" 
                                   id="code" 
                                   name="code" 
                                   maxlength="6" 
                                   placeholder ="Input Google Authenticator"
                                   pattern="[0-9]{6}" 
                                   required 
                                   autocomplete="off">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>

                        <div class="button text-center">
                            <button type="submit" class="btn btn-lg btn-gradient-08">
                                Verify OTP
                                <div class="loader" id="loader"></div>
                            </button>
                        </div>
                    </form>

                    <div class="form-text"> 
                        <a href="recovery.php">Lost access?</a> 
                    </div>

                    <div class="text-center mt-3">
                        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
                    </div>
                </div>      
            </div>
        </div>
    </div>  

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verifyForm');
            const codeInput = document.getElementById('code');
            const errorMessage = document.getElementById('errorMessage');
            const loader = document.getElementById('loader');

            // Only allow numeric input
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^\d]/g, ''); // Allow only digits
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Verifying...';
                loader.style.display = 'inline-block'; // Show loader
                errorMessage.style.display = 'none';

                fetch('verify_2fa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'code': codeInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        errorMessage.textContent = data.message;
                        errorMessage.style.display = 'block';
                        codeInput.value = ''; // Clear input
                        codeInput.focus(); // Focus on the input
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessage.textContent = 'An error occurred. Please try again.';
                    errorMessage.style.display = 'block';
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                    loader.style.display = 'none'; // Hide loader
                });
            });
        });
    </script>

    <script src="../pagesstyle/assets/vendors/js/base/jquery.min.js"></script>
    <script src="../pagesstyle/assets/vendors/js/base/core.min.js"></script>  
    <script src="../pagesstyle/assets/vendors/js/app/app.min.js"></script>
</body>
</html>
