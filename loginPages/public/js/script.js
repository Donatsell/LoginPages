// Login Function with Enhanced Security
function loadDoc() {
    const nameInput = document.getElementById("nama");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("errorMessage");

    // Reset previous error messages
    errorMessage.textContent = "";

    // Client-side validation
    if (!validateLoginInput(nameInput.value, passwordInput.value, errorMessage)) {
        return;
    }

    // AJAX login request
    fetch('/login/authenticate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCSRFToken() // Implement CSRF protection
        },
        body: JSON.stringify({
            username: nameInput.value,
            password: passwordInput.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/dashboard'; // Redirect to dashboard
        } else {
            errorMessage.textContent = data.message;
        }
    })
    .catch(error => {
        errorMessage.textContent = "An unexpected error occurred";
    });
}

// Client-side input validation
function validateLoginInput(username, password, errorElement) {
    // Trim inputs
    username = username.trim();
    password = password.trim();

    // Check for empty fields
    if (username === "") {
        errorElement.textContent = "Username cannot be empty";
        return false;
    }

    if (password === "") {
        errorElement.textContent = "Password cannot be empty";
        return false;
    }

    // Additional validation rules
    if (username.length < 3) {
        errorElement.textContent = "Username must be at least 3 characters";
        return false;
    }

    if (password.length < 8) {
        errorElement.textContent = "Password must be at least 8 characters";
        return false;
    }

    return true;
}

// CSRF Token Generation (placeholder)
function getCSRFToken() {
    // Implement actual CSRF token generation
    return 'your-csrf-token';
}// Register Function
function registerUser() {
    const nameInput = document.getElementById("registerName");
    const emailInput = document.getElementById("registerEmail");
    const passwordInput = document.getElementById("registerPassword");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    const errorMessage = document.getElementById("registerErrorMessage");

    errorMessage.textContent = "";

    if (nameInput.value === "") {
        errorMessage.textContent = "Name cannot be empty.";
        nameInput.focus();
        return;
    }
    if (emailInput.value === "") {
        errorMessage.textContent = "Email cannot be empty.";
        emailInput.focus();
        return;
    }
    if (passwordInput.value === "") {
        errorMessage.textContent = "Password cannot be empty.";
        passwordInput.focus();
        return;
    }
    if (confirmPasswordInput.value === "") {
        errorMessage.textContent = "Confirm Password cannot be empty.";
        confirmPasswordInput.focus();
        return;
    }
    if (passwordInput.value !== confirmPasswordInput.value) {
        errorMessage.textContent = "Passwords do not match!";
        return;
    }

    // Proceed to send the registration data to the server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'register.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            errorMessage.textContent = "Registration failed. Please try again.";
        } else {
            alert("Registration successful!");
            window.location.href = "index.html"; // Redirect to login
        }
    };
    xhr.send(`name=${encodeURIComponent(nameInput.value)}&email=${encodeURIComponent(emailInput.value)}&password=${encodeURIComponent(passwordInput.value)}`);
}

// Password Reset Function
function resetPassword() {
    const emailInput = document.getElementById("forgotEmail");
    const errorMessage = document.getElementById("resetErrorMessage");

    errorMessage.textContent = "";

    if (emailInput.value === "") {
        errorMessage.textContent = "Please enter your email.";
        return;
    }

    // Proceed to send the password reset request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'reset_password.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            errorMessage.textContent = "Failed to reset password. Please try again.";
            } else {
            alert("Password reset link sent!");
            window.location.href = "index.html"; // Redirect to login
            }
    };
    xhr.send(`email=${encodeURIComponent(emailInput.value)}`);
}
