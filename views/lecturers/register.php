<?php
session_start();

$message = '';
$messageType = '';

// Retrieve session messages
if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $messageType = 'error';
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $messageType = 'success';
    unset($_SESSION['success']);
}

require_once '../../config/db.php';

// Fetch courses along with their department names
$query = 'SELECT courses.id, courses.course_code, departments.department_name 
          FROM courses 
          JOIN departments ON courses.department_id = departments.id';
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lecturer_number = $_POST['lecturer_number'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $course_id = $_POST['course_id'] ?? '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: register.php');
        exit;
    }

    // Validate password match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: register.php');
        exit;
    }

    // Check if the lecturer already exists
    $stmt = $pdo->prepare('SELECT * FROM lecturers WHERE email = :email OR lecturer_number = :lecturer_number');
    $stmt->execute(['email' => $email, 'lecturer_number' => $lecturer_number]);
    $existingLecturer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingLecturer) {
        $_SESSION['error'] = 'Email or Lecturer Number already registered. Please login.';
        header('Location: register.php');
        exit;
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert the new lecturer into the database
        $stmt = $pdo->prepare('INSERT INTO lecturers (lecturer_number, name, email, password, course_id) VALUES (:lecturer_number, :name, :email, :password, :course_id)');
        $stmt->execute([
            'lecturer_number' => $lecturer_number,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'course_id' => $course_id
        ]);

        if ($stmt) {
            $_SESSION['success'] = 'Registration successful! You can now login.';
            header('Location: register.php');
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: register.php');
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Register</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .register-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            text-align: center;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            height: 40px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="register-container">
        <h2>Lecturer Registration</h2>
        <?php if ($message): ?>
            <div class="message <?= $messageType; ?>"><?= $message; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST" class="validate-form">
            <div class="form-group">
                <label for="lecturer_number">Lecturer Number:</label>
                <input type="text" name="lecturer_number" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="course_id">Course and Department:</label>
                <select name="course_id" required>
                    <option value="" disabled selected>Select Course and Department</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id']; ?>">
                            <?= $course['course_code'] . ' - ' . $course['department_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-message"></span>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="login-link">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </div>

    <?php require_once '../partials/footer.php'; ?>

    <script>
        window.onload = function() {
            setTimeout(function() {
                var messages = document.querySelectorAll('.message');
                messages.forEach(function(message) {
                    message.classList.add('fade-out');
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                });
            }, 5000);
        };
    </script>
</body>
</html>
