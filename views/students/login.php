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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="login-container">
        <h2>Student Login</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="../../controllers/StudentAuthController.php?action=login" method="POST" class="validate-form">
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
            Don't have an account? <a href="http://localhost/uniexam/views/students/register.php">Register here</a>.
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
