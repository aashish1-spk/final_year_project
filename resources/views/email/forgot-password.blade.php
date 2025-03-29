<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password Email</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #2ecc71;
            color: #fff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }
        .button:hover {
            background-color: #27ae60;
        }
        .footer {
            font-size: 14px;
            color: #aaa;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Forgot Password Request</h1>
        
        <p>Hello <strong>{{ $mailData['user']->name }}</strong>,</p>
        
        <p>We received a request to reset your password. If you made this request, you can reset your password by clicking the link below:</p>

        <p>
            <a href="{{ route('account.resetPassword', $mailData['token']) }}" class="button">Click Here to Reset Password</a>
        </p>

        <p>If you did not request a password reset, please ignore this email.</p>

        <div class="footer">
            <p>Thanks,</p>
            <p>Pahilo job</p>
        </div>
    </div>
</body>
</html>
