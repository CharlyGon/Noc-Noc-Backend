<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset Password</title>
</head>

<body>
    <div class="email-container">
        <p>Hello {{ $user->name }}!</p>

        <p>You are receiving this email because we received a password reset request for your account.</p>

        <p>This password reset link will expire in 60 minutes.</p>
        <p>To reset your password, click the following link:</p>
        <a href="{{ $resetUrl }}" class="button">Reset Password</a>

        <p>If you did not request a password reset, no further action is required.</p>

        <p>Regards,<br>Gonzalo Fernández</p>
    </div>
</body>

</html>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f6f6f6;
    }

    .email-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .button {
        display: block;
        width: 200px;
        margin: 20px auto;
        padding: 10px;
        background-color: #3498db;
        border: none;
        border-radius: 5px;
        color: #ffffff;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
    }
</style>
