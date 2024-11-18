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
        // $gender = $conn->real_escape_string($_POST['GENDER']);
        $phone = $conn->real_escape_string($_POST['PHONE']);
        $cusuname = $conn->real_escape_string($_POST['CUSUNAME']);
        $password = password_hash($_POST['CUSPASS'], PASSWORD_DEFAULT);
        $term = 1;
        $datejoin = date('Y-m-d H:i:s');
        $code = $conn->real_escape_string(md5(rand()));

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

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblcustomer WHERE CUSUNAME='{$cusuname}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$cusuname} - This username already exists.</div>";
        } else {    
                $sql = "INSERT INTO tblcustomer (FNAME, LNAME, CITYADD, LMARK, PHONE, CUSUNAME, CUSPASS, TERMS, DATEJOIN, code) 
                        VALUES ('$fname', '$lname', '$cityadd', '$lmark', '$phone', '$cusuname', '$password', '$term', '$datejoin', '$code')";
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
        html, body {
            background-color: #faf9f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        .signup-container {
            max-width: 500px;
            width: 90%;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
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
        .terms{
            cursor:pointer;
        }

        .custom-shape-divider-top-1728099492 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: 1;
        }

        .custom-shape-divider-top-1728099492 svg {
            position: relative;
            display: block;
            width: calc(173% + 1.3px);
            height: 345px;
        }

        .custom-shape-divider-top-1728099492 .shape-fill {
            fill: #FD2323;
        }
    </style>
</head>
<body>
<div class="custom-shape-divider-top-1728099492">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
    </svg>
</div>
    <div class="signup-container">
        <h2 class="text-center mb-3">Sign Up</h2>
        <p class="text-center mb-4">Please fill in the details to sign up!</p>
        <?php echo $msg; ?>
        <form id="signupForm" action="" name="personal" method="POST" enctype="multipart/form-data">
        <input class="proid" type="hidden" name="proid" id="proid" value="">
            <div class="row mb-3">
                <div class="col">
                    <input type="text"  id="FNAME" name="FNAME" class="form-control" placeholder="First name" required>
                </div>
                <div class="col">
                    <input type="text" id="LNAME" name="LNAME" class="form-control" placeholder="Last name" required>
                </div>
            </div>
            <!-- <div class="mb-3">
                <select  id="GENDER" name="GENDER" class="form-select" required>
                    <option value="" selected disabled>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div> -->
            <p class="text-left">Ex: Burgos St. Mancilang, Madridejos Cebu.</p>
            <div class="mb-3">
                <textarea class="form-control" id="CITYADD" name="CITYADD" rows="3" placeholder="Street/Brgy./Municipality/Province" value=""  required></textarea>
            </div>
            <p class="text-left">Ex: Next to the City Hall</p>
            <div class="mb-3">
                <textarea class="form-control" id="LMARK" name="LMARK" rows="2" placeholder="Landmarks" value=""  required></textarea>
            </div>
            <p class="text-left">Ex: johndoe@gmail.com</p>
            <div class="mb-3">
                <input type="email"  id="CUSUNAME" name="CUSUNAME" class="form-control" placeholder="Email" required oninput="validateEmail()" required>
            </div>
            <div class="mb-3 password-container">
                <input type="password" class="form-control" id="CUSPASS" name="CUSPASS" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </span>
            </div>
            <p class="text-left">Ex: 09692870485</p>
<div class="mb-3">
  <input type="tel" id="PHONE" name="PHONE" value="" inputmode="numeric" pattern="[0-9]{11}" maxlength="11" class="form-control" placeholder="Contact Number" required oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);">
</div>

            <div class="mb-3">
            <input type="checkbox" id="conditionterms" name="conditionterms"
                                                             value="checkbox" required>
                                                         By <small>clicking this box, you are agreeing the <a
                                                                 class="toggle-modal"
                                                                 onclick=" OpenPopupCenter('../customer/terms.php','Terms And Conditions','600','600')"><b class="terms">Terms
                                                                     and conditions</b></a></small>
            </div>
            <button type="submit" name="submit" class="btn btn-danger w-100 mb-3">Sign Up</button>
            <p class="text-center mb-0">Already have an account? <a href="index.php" class="text-danger">Sign In</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('CUSPASS');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function OpenPopupCenter(pageURL, title, w, h) {
    var left = (screen.width - w) / 2;
    var top = (screen.height - h) / 4; // for 25% - devide by 4  |  for 33% - devide by 3
    var targetWin = window.open(pageURL, title,
        'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' +
        w + ', height=' + h + ', top=' + top + ', left=' + left);
}

// document.getElementById('signupForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     let formData = new FormData(this);
//     formData.append('submit', 'true'); // Add this line to ensure 'submit' is sent

//     fetch('../customer/controller.php?action=add', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.text())
//     .then(text => {
//         console.log('Raw server response:', text);
//         return JSON.parse(text);
//     })
//     .then(data => {
//         if (data.status === 'info') {
//             Swal.fire({
//                 icon: 'info',
//                 title: 'Oops...',
//                 text: data.message,
//             });
//         } else if (data.status === 'success') {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Success!',
//                 text: data.message,
//                 showConfirmButton: false,
//                 timer: 1500
//             }).then(() => {
//                 window.location.href = data.redirect;
//             });
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         Swal.fire({
//             icon: 'error',
//             title: 'Oops...',
//             text: 'Something went wrong: ' + error.message,
//         });
//     });
// });

    </script>
</body>
</html>
