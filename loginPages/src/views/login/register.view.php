<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Create Account</h2>
        <form id="registerForm">
            <input type="text" id="registerName" placeholder="Full Name" required />
            <input type="email" id="registerEmail" placeholder="Email" required />
            <input type="password" id="registerPassword" placeholder="Password" required />
            <input type="password" id="confirmPassword" placeholder="Confirm Password" required />
            <button type="button" onclick="registerUser()">Sign Up</button>
            <div id="registerErrorMessage" class="error"></div>
        </form>
        <p><a href="/loginPages/login">Already have an account?</a></p>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
