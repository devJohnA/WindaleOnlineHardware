<?php require_once "../server.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: ../index.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            background-color: #faf9f6;
        }
        .login-now-container {
            max-width: 300px;
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
        .custom-shape-divider-bottom-1728097337 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 1;
        }

        .custom-shape-divider-bottom-1728097337 svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 260px;
            transform: rotateY(180deg);
        }

        .custom-shape-divider-bottom-1728097337 .shape-fill {
            fill: #FD2323;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
<div class="custom-shape-divider-bottom-1728097337">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
    </svg>
</div>
    <div class="login-now-container bg-white p-4 rounded shadow text-center">
    <?php 
            if(isset($_SESSION['info'])){
                ?>
                <div class="alert alert-success text-center">
                    <?php echo $_SESSION['info']; ?>
                </div>
                <?php
            }
            ?>
        <a href="index.php" class="btn btn-danger w-100">Login Now</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
