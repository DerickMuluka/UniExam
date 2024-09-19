<?php
session_start();

$message = '';
$messageType = '';

if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $messageType = 'error';
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $messageType = 'success';
    unset($_SESSION['success']);
}

// Fetch courses from the database for the dropdown
require_once '../../config/db.php';
$stmt = $pdo->query('SELECT id, course_name FROM courses');
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
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

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
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
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="register-container">
        <h2>Student Registration</h2>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="../../controllers/StudentAuthController.php?action=register" method="POST" class="validate-form">
            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input type="text" id="registration_number" name="registration_number" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="course_id">Course</label>
                <select id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>">
                            <?php echo $course['course_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-message"></span>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <p class="login-link">
            Already have an account? <a href="http://localhost/uniexam/views/students/login.php">Login here</a>.
        </p>
    </div>

    <?php require_once '../partials/footer.php'; ?>

    <script src="/js/validation.js"></script>
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
