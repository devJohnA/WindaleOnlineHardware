
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>2FA Recovery - Windale Hardware</title>
    <meta name="description" content="2FA Recovery Page">
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
        .form-text {
            text-align: center;
            margin-top: 15px;
        }
        .group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .material-input input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            box-sizing: border-box;
        }
        .material-input .highlight,
        .material-input .bar {
            display: none;
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
                        <img src="../pagesstyle/assets/img/admin.png" alt="..." class="img-fluid rounded-circle">
                    </div>
                    <h3>2FA Recovery</h3>

                    <!-- Display error or success messages -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Recovery Form -->
                    <form id="recoveryForm">
                        <div class="group material-input">
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter your registered email" 
                                   required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                        </div>

                        <div class="button text-center">
                            <button type="submit" class="btn btn-lg btn-gradient-04">
                                Send Recovery Email
                                <div class="loader" id="loader"></div>
                            </button>
                        </div>
                    </form>

                    <div class="form-text">
                        <a href="authenticator.php">Back to Login</a>
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
            const form = document.getElementById('recoveryForm');
            const emailInput = document.getElementById('email');
            const errorMessage = document.getElementById('errorMessage');
            const loader = document.getElementById('loader');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Sending...';
                loader.style.display = 'inline-block'; // Show loader
                errorMessage.style.display = 'none';

                fetch('process_recovery.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'email': emailInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    errorMessage.className = 'alert alert-' + (data.success ? 'success' : 'danger');
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                    
                    if (data.success) {
                        form.reset(); // Reset form if successful
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessage.className = 'alert alert-danger';
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
