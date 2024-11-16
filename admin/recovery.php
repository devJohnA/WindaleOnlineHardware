<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Recovery - Windale Hardware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .recovery-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recovery-container">
            <div class="logo">
                <h2>Windale Hardware</h2>
                <p class="text-muted">2FA Recovery</p>
            </div>

            <form id="recoveryForm">
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="Enter your registered email"
                           required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Send Recovery Email</button>
                    <a href="authenticator.php" class="btn btn-outline-secondary">Back to Login</a>
                </div>

                <div id="recoveryMessage" class="alert mt-3" style="display: none;"></div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('recoveryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            const message = document.getElementById('recoveryMessage');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Sending...';
            message.style.display = 'none';

            // Send recovery request
            fetch('process_recovery.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'email': this.email.value
                })
            })
            .then(response => response.json())
            .then(data => {
                message.className = 'alert mt-3 ' + (data.success ? 'alert-success' : 'alert-danger');
                message.textContent = data.message;
                message.style.display = 'block';
                
                if (data.success) {
                    this.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                message.className = 'alert mt-3 alert-danger';
                message.textContent = 'An error occurred. Please try again.';
                message.style.display = 'block';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    </script>
</body>
</html>