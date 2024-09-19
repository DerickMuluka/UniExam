<?php
require_once '../config/db.php';
session_start();

class StudentAuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        // Validate the email format
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $_SESSION['error'] = 'Invalid email format.';
            header('Location: http://localhost/uniexam/views/students/login.php');
            exit;
        }

        // SQL query to fetch student by email
        $stmt = $this->pdo->prepare('SELECT * FROM students WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['student'] = $student;
            $_SESSION['success'] = 'Login successful! Welcome back.';
            header('Location: http://localhost/uniexam/views/students/student_dashboard.php');
        } else {
            $_SESSION['error'] = 'Invalid credentials. Please try again.';
            header('Location: http://localhost/uniexam/views/students/login.php');
        }
        exit;
    }

    public function register($registration_number, $name, $email, $password, $confirmPassword, $course_id) {
        // Validate the email format
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $_SESSION['error'] = 'Invalid email format.';
            header('Location: http://localhost/uniexam/views/students/register.php');
            exit;
        }

        // Check if passwords match
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: http://localhost/uniexam/views/students/register.php');
            exit;
        }

        // Check if registration number or email already exists
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM students WHERE registration_number = :registration_number OR email = :email');
        $stmt->execute([
            'registration_number' => $registration_number,
            'email' => $email
        ]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error'] = 'Registration number or email already exists.';
            header('Location: http://localhost/uniexam/views/students/register.php');
            exit;
        }

        // Insert new student record
        $stmt = $this->pdo->prepare('INSERT INTO students (registration_number, name, email, password, course_id) VALUES (:registration_number, :name, :email, :password, :course_id)');
        $result = $stmt->execute([
            'registration_number' => $registration_number,
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'course_id' => $course_id
        ]);

        if ($result) {
            $_SESSION['success'] = 'Registration successful! Please login to continue.';
            header('Location: http://localhost/uniexam/views/students/login.php');
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: http://localhost/uniexam/views/students/register.php');
        }
        exit;
    }
}

// Initialize the controller
$controller = new StudentAuthController($pdo);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] === 'register') {
        $controller->register(
            $_POST['registration_number'],
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['confirm_password'],
            $_POST['course_id']
        );
    } elseif (isset($_GET['action']) && $_GET['action'] === 'login') {
        $controller->login(
            $_POST['email'],
            $_POST['password']
        );
    }
}
