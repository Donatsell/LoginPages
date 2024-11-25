<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pages</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <script src="js/script.js"></script>
    <div class="form-container">
        <h2>Login</h2>
        <form action="" id="formId">
            <input type="text" id="nama" placeholder="username" required />
            <input type="password" id="password" placeholder="password" required />
            <button type="button" onclick="loadDoc()">Sign In</button>
            <div class="links-container">
                <p><a href="login/register">Don't have an account?</a></p>
                <p><a href="login/forgotPassword">Forgot Password?</a></p>
            </div>
            <div id="errorMessage" class="error"></div>
        </form>
        <div id="greeting"></div>
    </div>
</body>
</html>

