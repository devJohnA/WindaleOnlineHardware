<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        .btn-otp {
            display: inline-block;
            margin: 15px 0;
            padding: 15px 30px;
            font-size: 16px;
            text-decoration: none;
            background-color: #fd2323;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-otp:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        a:active {
            transform: translateY(0);
        }
        .instructions {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
        }

        .login {
            font-size: 14px;
            color: black;
            margin-top: 10px;
            font-weight:bold;
            cursor:pointer;
        }

        .login a{
            text-decoration:none;
            color: grey;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Forgot Password</h2>
        <p>Choose your preferred recovery method:</p>
        <a href="sms/send-otp.php" class="btn-otp">Send SMS OTP</a>
        <a href="forgot-password.php" class="btn-otp">Send Gmail OTP</a>
        <div class="instructions">
            <p>If you have any trouble, just chat with our Admin support!</p>
        </div>

        <div class="login">
            <a href="index.php"> Back to Login</a>
        </div>
    </div>

</body>
</html>
