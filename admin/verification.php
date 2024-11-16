<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .verification-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
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
<body class="bg-light">
    <div class="container">
        <div class="verification-container bg-white">
            <div class="text-center">
                <h2 class="mb-4">Email Verification</h2>
            </div>
            
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
                <div class="mb-4">
                    <label for="email" class="form-label">Enter your Gmail address</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="example@gmail.com"
                           pattern="[a-z0-9._%+-]+@gmail\.com$"
                           required>
                    <div class="form-text">We'll send a verification link to this email.</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center">
                        Send Verification Email
                        <div class="loader" id="loader"></div>
                    </button>
                </div>

                <div class="text-center mt-3">
                    <div id="message" class="alert" style="display: none;"></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const messageDiv = document.getElementById('message');
            const submitButton = this.querySelector('button[type="submit"]');
            const loader = document.getElementById('loader');
            
            submitButton.disabled = true;
            loader.style.display = 'inline-block';
            messageDiv.style.display = 'none';
            
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
                    document.getElementById('email').value = '';
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
</body>
</html>