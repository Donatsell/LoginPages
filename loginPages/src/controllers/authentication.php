<?php
class Authentication {
    private $db;
    
    public function __construct() {
        // Secure database connection
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=loginpages", 
                "root", 
                "", 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    // Secure login method
    public function login($username, $password) {
        // Input validation and sanitization
        $username = $this->sanitizeInput($username);
        $password = $this->sanitizeInput($password);

        // Prevent brute force attacks
        if ($this->checkBruteForce($username)) {
            throw new Exception("Too many login attempts. Please try again later.");
        }

        // Prepare and execute login query
        $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set secure session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_login'] = time();
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

            // Log successful login
            $this->logLoginAttempt($user['id'], true);
            return true;
        }

        // Log failed login attempt
        $this->logLoginAttempt($user['id'] ?? null, false);
        return false;
    }

    // Secure registration method
    public function register($username, $email, $password, $confirm_password) {
        // Validate inputs
        $this->validateRegistrationInputs($username, $email, $password, $confirm_password);

        // Check if username or email already exists
        if ($this->userExists($username, $email)) {
            throw new Exception("Username or email already exists");
        }

        // Hash password securely
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        // Prepare registration query
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password) 
             VALUES (:username, :email, :password)"
        );

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Forgot Password Method
    public function resetPassword($email) {
        // Validate email
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception("Invalid email format");
        }

        // Generate secure reset token
        $reset_token = bin2hex(random_bytes(32));
        $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->db->prepare(
            "UPDATE users SET reset_token = :token, reset_expiry = :expiry 
             WHERE email = :email"
        );

        $stmt->bindParam(':token', $reset_token, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $reset_expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Security Helper Methods
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    private function validateRegistrationInputs($username, $email, $password, $confirm_password) {
        // Comprehensive input validation
        if (strlen($username) < 3 || strlen($username) > 50) {
            throw new Exception("Username must be between 3-50 characters");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }
    }

    private function userExists($username, $email) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email"
        );
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    private function checkBruteForce($username) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM login_attempts 
            WHERE username = :username AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)"
        );
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() >= 5;
    }

    private function logLoginAttempt($user_id, $success) {
        $stmt = $this->db->prepare(
            "INSERT INTO login_attempts (user_id, username, ip_address, success) 
            VALUES (:user_id, :username, :ip, :success)"
        );
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindParam(':success', $success, PDO::PARAM_BOOL);
        $stmt->execute();
    }
}
