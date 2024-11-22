<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="../img/windales.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            padding: 25px; 
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 350px;  
        }
        h2 {
            margin-bottom: 15px;  
            font-size: 20px;  
            color: #333;
        }
        .info-text {
            color: #666;
            font-size: 13px; 
            margin: 8px 0 15px 0; 
            line-height: 1.4;  
            text-align: left;
            padding: 10px;  
            background-color: #f8f8f8;
            border-radius: 5px;
        }
        .method-info {
            font-size: 12px;  
            color: #666;
            margin: 3px 0 10px 35px;  
            text-align: left;
        }
        .recovery-options {
            display: flex;
            flex-direction: column;
            gap: 10px;  
            margin: 15px 0; 
        }
        .recovery-link {
            display: flex;
            align-items: center;
            padding: 12px 15px; 
            color: #333;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 14px; 
        }
        .recovery-link:hover {
            background-color: #f8f8f8;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .recovery-link i {
            margin-right: 12px;  
            font-size: 16px;  
            color: #fd2323;
        }
        .instructions {
            font-size: 13px;  
            color: #555;
            margin-top: 15px;  
        }
        .instructions p {
            margin: 5px 0;  
        }
        .login {
            margin-top: 15px;  
        }
        .login a {
            text-decoration: none;
            color: #666;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px; 
            font-size: 13px;  
        }
        .login a:hover {
            color: #fd2323;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <div class="info-text">
            To reset your password, follow these steps:
            <br> Select your preferred recovery method below
        </div>
        <p>Choose your preferred recovery method:</p>
        <div class="recovery-options">
            <a href="sms/send-otp" class="recovery-link">
                <i class="fas fa-mobile-alt"></i>
                Reset via SMS
            </a>
            <div class="method-info">Receive a verification code on your registered phone number</div>
            
            <a href="forgot-password" class="recovery-link">
                <i class="fab fa-google"></i>
                Reset via Email
            </a>
            <div class="method-info">Get a verification code through your Email account</div>
            
            <!-- <a href="email/send-email" class="recovery-link">
                <i class="fas fa-envelope"></i>
                Email Recovery
            </a>
            <div class="method-info">Receive a verification code on your registered email address</div> -->
        </div>
        <div class="instructions">
            <p>If you have any trouble, just chat with our Admin support!</p>
        </div>
        <div class="login">
            <a href="index">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>