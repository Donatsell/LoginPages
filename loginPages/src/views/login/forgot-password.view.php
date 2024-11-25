<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Forgot Password</h2>
        <form id="forgotPasswordForm">
            <input type="email" id="forgotEmail" placeholder="Enter your email" required />
            <button type="button" onclick="resetPassword()">Reset Password</button>
            <div id="resetErrorMessage" class="error"></div>
        </form>
        <p><a href="/loginPages/login">Back to Login</a></p>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
