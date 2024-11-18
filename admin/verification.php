<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Team Session</title>
        <meta name="description" content="Elisyam is a Web App and Admin Dashboard Template built with Bootstrap 4">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Google Fonts -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
        <script>
          WebFont.load({
            google: {"families":["Montserrat:400,500,600,700","Noto+Sans:400,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!-- Favicon -->
        <link rel="icon" href="../img/windales.png">
        <!-- Stylesheet -->
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
        <!-- Begin Preloader -->
        <div id="preloader">
            <div class="canvas">
                <div class="spinner"></div>   
            </div>
        </div>
        <!-- End Preloader -->
        <!-- Begin Section -->
        <div class="container-fluid h-100 overflow-y">
            <div class="row flex-row h-100">
                <div class="col-12 my-auto">
                    <div class="lock-form mx-auto">
                        <div class="photo-profil">
                            <div class="icon"><i class="la la-unlock"></i></div>
                            <img src="../pagesstyle/assets/img/admin.png" alt="..." class="img-fluid rounded-circle">
                        </div>
                        <h3>Authorized Users</h3>

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

                        <form id="emailForm">
                            <div class="group material-input">
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Send Email for Verification"
                                       pattern="[a-z0-9._%+-]+@gmail\.com$"
                                       required>
                                <span class="highlight"></span>
                                <span class="bar"></span>
                            </div>
                        </form>
                        <div class="button text-center">
                            <button type="submit" class="btn btn-lg btn-gradient-01" id="submitBtn">
                                Send Verification
                                <div class="loader" id="loader"></div>
                            </button>
                        </div>
                        <div class="form-text" style="text-align:center; margin-top:8px;">We'll send a verification link to this email.</div>
                        
                        <div class="text-center mt-3">
                            <div id="message" class="alert" style="display: none;"></div>
                        </div>
                    </div>      
                </div>
            </div>
            <!-- End Container -->
        </div>  

        <script>
            document.getElementById('submitBtn').addEventListener('click', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                const messageDiv = document.getElementById('message');
                const submitButton = this;
                const loader = document.getElementById('loader');
                
                // Disable button and show loader
                submitButton.disabled = true;
                loader.style.display = 'inline-block';
                messageDiv.style.display = 'none';

                // Make fetch request to send_verification.php
                fetch('send_verification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.json())
                .then(data => {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'alert ' + (data.success ? 'alert-success' : 'alert-danger');
                    messageDiv.style.display = 'block';
                    
                    if (data.success) {
                        document.getElementById('email').value = ''; // Clear the email input
                    }
                })
                .catch(error => {
                    messageDiv.textContent = 'An error occurred. Please try again.';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.style.display = 'block';
                })
                .finally(() => {
                    submitButton.disabled = false;
                    loader.style.display = 'none';
                });
            });
        </script>

        <script src="../pagesstyle/assets/vendors/js/base/jquery.min.js"></script>
        <script src="../pagesstyle/assets/vendors/js/base/core.min.js"></script>  
        <script src="../pagesstyle/assets/vendors/js/app/app.min.js"></script>
    </body>
</html>
