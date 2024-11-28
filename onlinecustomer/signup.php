<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';
    include '../admin/dbcon/conn.php';
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fname = $conn->real_escape_string($_POST['FNAME']);
        $lname = $conn->real_escape_string($_POST['LNAME']);
        $cityadd = $conn->real_escape_string($_POST['CITYADD']);
        $lmark = $conn->real_escape_string($_POST['LMARK']);
        $phone = $conn->real_escape_string($_POST['PHONE']);
        $cusuname = $conn->real_escape_string($_POST['CUSUNAME']);
        $password = $_POST['CUSPASS']; // Get raw password for validation
        $term = 1;
        $datejoin = date('Y-m-d H:i:s');
        $code = $conn->real_escape_string(md5(rand()));
    
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
    
        // Validate email
        if (!filter_var($cusuname, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter a valid email address.'
                });
            </script>";
            exit;
        }
    
        // Validate password
        $passwordValidation = validatePassword($password);
        if ($passwordValidation !== true) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: '$passwordValidation'
                });
            </script>";
            exit;
        }
    
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblcustomer WHERE CUSUNAME='{$cusuname}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$cusuname} - This username already exists.</div>";
        } else {    
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO tblcustomer (FNAME, LNAME, CITYADD, LMARK, PHONE, CUSUNAME, CUSPASS, TERMS, DATEJOIN, code) 
                    VALUES ('$fname', '$lname', '$cityadd', '$lmark', '$phone', '$cusuname', '$hashedPassword', '$term', '$datejoin', '$code')";
            $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    $mail = new PHPMailer(true);

                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'delacruzjohnanthon@gmail.com';
                        $mail->Password   = 'rpabbkqjeldjveyr';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;

                        $mail->setFrom('delacruzjohnanthon@gmail.com');
                        $mail->addAddress($cusuname);

                        $mail->isHTML(true);
                        $mail->Subject = 'Windale Hardware Inc.';
                        $mail->Body    = 'Here is the verification link <b><a href="https://windalehardware.com/onlinecustomer/?verification='.$code.'">https://windalehardware.com/onlinecustomer/?verification='.$code.'</a></b>';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something went wrong.</div>";
                }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="icon" href="../img/windales.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .signup-container {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 1rem;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 1.2rem;
        }

        .signup-header h2 {
            color: #333;
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
        }

        .signup-header p {
            color: #666;
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .form-control {
            border: 1px solid #ddd;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            margin-bottom: 0.8rem;
            font-size: 0.9rem;
            height: auto;
        }

        .form-control:focus {
            border-color: #fd2323;
            box-shadow: 0 0 0 0.2rem rgba(253, 35, 35, 0.1);
        }

        .form-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.2rem;
        }

        .example-text {
            font-size: 0.7rem;
            color: #888;
            margin-bottom: 0.2rem;
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

        .btn-signup {
            background: #fd2323;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 500;
            width: 100%;
            margin-top: 0.8rem;
            color: white;
            font-size: 0.9rem;
        }

        .btn-signup:hover {
            background: #e61e1e;
        }

        .terms-check {
            margin-top: 0.8rem;
            font-size: 0.75rem;
            color: #666;
        }

        .terms-check a {
            color: #fd2323;
            text-decoration: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: #666;
        }

        .login-link a {
            color: #fd2323;
            text-decoration: none;
            font-weight: 500;
        }

        .input-group {
            position: relative;
            margin-bottom: 0.8rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.8rem;
            top: 40%;
            transform: translateY(-50%);
            color: #666;
            cursor: pointer;
            z-index: 10;
        }

        textarea.form-control {
            min-height: 45px;
            resize: vertical;
        }

        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Sign Up</h2>
            <p>Please fill in the details to sign up!</p>
        </div>

        <form action="" method="POST"  name="personal"  id="signupForm">
        <?php echo $msg; ?>
            <div class="row">
                <div class="col-6 mb-2">
                    <label class="form-label">First Name</label>
                    <input type="text" name="FNAME" class="form-control" required>
                </div>
                <div class="col-6 mb-2">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="LNAME" class="form-control" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Address</label>
                <div class="example-text">Ex: Burgos St. Mancilang, Madridejos Cebu.</div>
                <textarea class="form-control" name="CITYADD" required></textarea>
            </div>

            <div class="mb-2">
                <label class="form-label">Landmarks</label>
                <div class="example-text">Ex: Next to the City Hall</div>
                <textarea class="form-control" name="LMARK" required></textarea>
            </div>

            <div class="mb-2">
                <label class="form-label">Email</label>
                <div class="example-text">Ex: johndoe@gmail.com</div>
                <input type="email" name="CUSUNAME" class="form-control" required>
            </div>

            <div class="mb-2">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="CUSPASS" id="password" class="form-control" required>
                    <span class="password-toggle">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                </div>
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
            </div>

            <div class="mb-2">
                <label class="form-label">Contact Number</label>
                <div class="example-text">Ex: 09692870485</div>
                <input type="tel" name="PHONE" pattern="[0-9]{11}" maxlength="11" class="form-control" required>
            </div>

            <div class="terms-check">
    <input type="checkbox" required id="terms">
    <label for="terms">
        I agree to the 
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#termsModal">
            Terms and conditions
        </a>
    </label>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <?php
        // Include the terms content dynamically.
        include '../customer/terms.php';
        ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

            <button type="submit"  name="submit" class="btn btn-signup">Sign Up</button>

            <div class="login-link">
                Already have an account? <a href="index.php">Sign In</a>
            </div>
        </form>
    </div>

    <script>
        // Password visibility toggle
        document.querySelector('.password-toggle').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
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

        // Phone number validation
        document.querySelector('input[name="PHONE"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });

        const form = document.getElementById('signupForm');
form.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    
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
        
        // Show error message using SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Invalid Password',
            text: 'Please ensure all password requirements are met.',
            confirmButtonColor: '#fd2323'
        });
        
        // Focus on password field
        document.getElementById('password').focus();
    }
});

    </script>
</body>
</html>
