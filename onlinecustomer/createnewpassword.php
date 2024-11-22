<?php require_once "../server.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password</title>
    <link rel="icon" href="../img/windales.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            background-color: #faf9f6;
        }
        .new-password-container {
            max-width: 400px;
            width: 90%;
        }
        .btn-danger {
            background-color: #fd2323;
        }
        .btn-danger:hover {
            background-color: #f71d1d;
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .start-end {
            text-align: right;
        }
        .password-requirements {
            margin-top: 0.3rem;
        }
        .requirement {
            font-size: 0.7rem;
            color: #666;
            margin-bottom: 0.1rem;
            display: flex;
            align-items: center;
        }
        .requirement i {
            margin-right: 0.3rem;
            font-size: 0.65rem;
        }
        .requirement.valid {
            color: #28a745;
        }
        .requirement.invalid {
            color: #dc3545;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="new-password-container bg-white p-4 rounded shadow">
        <div class="start-end"> <img src="win.png" width="80" height="80"></div>
        <form action="../onlinecustomer/createnewpassword.php" method="POST" autocomplete="off" id="passwordResetForm">
            <h2 class="text-center mb-3">Create New Password</h2>
            <p class="text-center mb-4">Please enter your new password and confirm it.</p>
            <?php 
                if(isset($_SESSION['info'])){
                    ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['info']; ?>
                    </div>
                    <?php
                }
                if(count($errors) > 0){
                    ?>
                    <div class="alert alert-danger text-center">
                        <?php
                        foreach($errors as $showerror){
                            echo $showerror;
                        }
                        ?>
                    </div>
                    <?php
                }
            ?>
        <div class="password-requirements">
                    <div class="requirement" id="length">
                        <i class="fas fa-circle"></i> Min. 8 characters
                    </div>
                    <div class="requirement" id="uppercase">
                        <i class="fas fa-circle"></i> One uppercase
                    </div>
                    <div class="requirement" id="lowercase">
                        <i class="fas fa-circle"></i> One lowercase
                    </div>
                    <div class="requirement" id="number">
                        <i class="fas fa-circle"></i> One number
                    </div>
                    <div class="requirement" id="special">
                        <i class="fas fa-circle"></i> One special char
                    </div>
                </div>
            <div class="mb-3 password-container">
                <input type="password" name="password" id="password" class="form-control" placeholder="New Password" required>
                <span class="password-toggle">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </span>
               
            </div>
            <div class="mb-3 password-container">
                <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" required>
                <span class="password-toggle">
                    <i class="fas fa-eye" id="toggleCPassword"></i>
                </span>
            </div>
            <button type="submit" name="change-password" class="btn btn-danger w-100 mb-3">Reset Password</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Password validation
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            for (const [requirement, valid] of Object.entries(requirements)) {
                const element = document.getElementById(requirement);
                element.className = 'requirement ' + (valid ? 'valid' : 'invalid');
                const icon = element.querySelector('i');
                if (valid) {
                    icon.className = 'fas fa-check-circle';
                } else {
                    icon.className = 'fas fa-circle';
                }
            }
        });

        // Form submission validation
        const form = document.getElementById('passwordResetForm');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;
            
            // Check all password requirements
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Check if any requirement is not met
            const hasInvalidRequirement = Object.values(requirements).includes(false);
            
            if (hasInvalidRequirement) {
                e.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please ensure all password requirements are met.',
                    confirmButtonColor: '#fd2323'
                });
                return;
            }

            // Check if passwords match
            if (password !== cpassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords Do Not Match',
                    text: 'Please ensure both passwords are identical.',
                    confirmButtonColor: '#fd2323'
                });
                return;
            }
        });
    </script>
</body>
</html>