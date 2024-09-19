<?php
require_once '../config/db.php';
session_start();

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        // Validate email format
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $_SESSION['error'] = 'Invalid email format.';
            header('Location: http://localhost/uniexam/views/auth/login.php');
            exit;
        }

        // Prepare and execute SQL statement to find the user by email
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password and start the session if credentials are valid
        if ($user && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            $_SESSION['success'] = 'Login successful! Welcome back.';
            header('Location: http://localhost/uniexam/admin.php');
        } else {
            $_SESSION['error'] = 'Invalid credentials. Please try again.';
            header('Location: http://localhost/uniexam/views/auth/login.php');
        }
        exit;
    }

    public function logout() {
        // Destroy the session and redirect to the login page
        session_unset();
        session_destroy();
        $_SESSION['success'] = 'You have been logged out successfully.';
        header('Location: http://localhost/uniexam/views/auth/login.php');
        exit;
    }
}

// Check the action and call the corresponding method
if (isset($_GET['action'])) {
    $authController = new AuthController($pdo);
    switch ($_GET['action']) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $authController->login($email, $password);
            }
            break;
        case 'logout':
            $authController->logout();
            break;
    }
}
?>
