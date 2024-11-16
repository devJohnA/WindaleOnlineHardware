<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .verification-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .code-input {
            letter-spacing: 8px;
            font-size: 24px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container bg-white">
            <h2 class="text-center mb-4">Admin Authentication</h2>
            <form id="verifyForm">
                <div class="mb-4">
                    <label for="code" class="form-label">Enter Authentication Code</label>
                    <input type="text" 
                           class="form-control code-input" 
                           id="code" 
                           name="code" 
                           maxlength="6" 
                           pattern="[0-9]{6}" 
                           required 
                           autocomplete="off">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Verify</button>
                </div>
                <a href="recovery.php"> Lost an access?</a>
                <div class="text-center mt-3">
                    <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verifyForm');
            const codeInput = document.getElementById('code');
            const errorMessage = document.getElementById('errorMessage');

            // Only allow numeric input
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^\d]/g, '');
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Verifying...';
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
                        codeInput.value = '';
                        codeInput.focus();
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
                });
            });
        });
    </script>
</body>
</html>