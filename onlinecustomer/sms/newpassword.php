<?php
include '../conn.php';
session_start();

// Ensure user has verified OTP before they can reset the password
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: verify_otp.php");
    exit();
}

$success = '';
$error = '';

// Check for verification success message
if (isset($_SESSION['verification_success'])) {
    $success = $_SESSION['verification_success'];
    unset($_SESSION['verification_success']); // Clear the message after displaying
}

// Password validation function
function validatePassword($password) {
    // Minimum length of 8 characters
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    // Must contain at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    // Must contain at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    // Must contain at least one number
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }
    // Must contain at least one special character
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return "Password must contain at least one special character.";
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    $phone = $_SESSION['reset_phone'];

    // Validate password
    $passwordValidation = validatePassword($password);
    if ($passwordValidation !== true) {
        $error = $passwordValidation;
    }
    // Check if passwords match
    else if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the new password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the database query to update the password
        $stmt = $conn->prepare("UPDATE tblcustomer SET CUSPASS = ? WHERE PHONE = ?");
        $stmt->bind_param("ss", $hashed_password, $phone);

        if ($stmt->execute()) {
            unset($_SESSION['otp_verified']);
            unset($_SESSION['reset_phone']);
            $_SESSION['success_message'] = "Your password has been updated successfully!";
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Password update failed. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" href="../../img/windales.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }
        input[type="password"],
input[type="text"] {  /* Add styles for text input to match password input */
    padding: 10px;
    width: 100%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    height: 40px; /* Fixed height to prevent shifting */
    transition: all 0.3s ease; /* Smooth transition for any changes */
    padding-right: 40px; /* Space for the eye icon */
}

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 40px;
            cursor: pointer;
            color: #666;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #fd2323;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        button:hover {
            background-color: #e61e1e;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        .success {
            color: green;
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .password-requirements {
            text-align: left;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .requirement {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.2rem;
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
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="resetForm">
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
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required>
                <span class="password-toggle">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </span>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <span class="password-toggle">
                    <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                </span>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>

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
        document.getElementById('new-password').addEventListener('input', function(e) {
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
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
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
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please ensure all password requirements are met.',
                    confirmButtonColor: '#fd2323'
                });
                return;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords Do Not Match',
                    text: 'Please make sure your passwords match.',
                    confirmButtonColor: '#fd2323'
                });
                return;
            }
        });
    </script>
</body>
</html>