<?php
 require_once "server.php";
$email = $_SESSION['email'];
if($email == false){
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            background-color: #faf9f6;
        }
        .verification-container {
            max-width: 400px;
            width: 90%;
        }
        .btn-danger {
            background-color: #fd2323;
        }
        .btn-danger:hover {
            background-color: #f71d1d;
        }
        .start-end {
            text-align: right;
        }

        .custom-shape-divider-bottom-1728231997 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.custom-shape-divider-bottom-1728231997 svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 110px;
}

.custom-shape-divider-bottom-1728231997 .shape-fill {
    fill: #FD2323;
}
    </style>
</head>
<body style="background-color:hsl(49 26.8% 92% /1);" class="d-flex align-items-center justify-content-center">
<div class="custom-shape-divider-bottom-1728231997">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z" class="shape-fill"></path>
    </svg>
</div>
    <div class="verification-container bg-white p-4 rounded shadow">
        <div class="start-end"> <img src="../onlinecustomer/win.png" width="80" height="80"></div>
        <h2 class="text-center mb-3">Enter OTP</h2>
        <p class="text-center mb-4">Enter the otp sent to your email.</p>
        <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                     <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                         <?php echo $_SESSION['info']; ?>
                     </div>
                     <?php
                    }
                    ?>
                     <?php
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
        <form action="reset-code.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <input type="text" name="otp" class="form-control" placeholder="Enter otp code" required>
            </div>
            <button type="submit" name="check-reset-otp"  class="btn btn-danger w-100 mb-3"> Submit</button>
            <p class="text-center mb-0">Didn’t receive a code? <a href="forgot-password.php" class="text-danger">Resend Code</a></p>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
