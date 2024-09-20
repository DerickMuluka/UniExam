<?php
session_start();
require_once '../../config/db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM lecturers WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lecturer && password_verify($password, $lecturer['password'])) {
        $_SESSION['lecturer'] = $lecturer;
        $_SESSION['success'] = 'Login successful! Welcome back.';
        header('Location: lecturer_dashboard.php');
        exit;
    } else {
        $message = 'Invalid credentials. Please try again.';
        $messageType = 'error';
        $_SESSION['error'] = $message;
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Login</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="login-container">
        <h2>Lecturer Login</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="validate-form">
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
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="register-link">
            Don't have an account? <a href="http://localhost/uniexam/views/lecturers/register.php">Register here</a>.
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
