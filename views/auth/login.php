<?php
session_start();

// Display error or success messages if available
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
    <title>Login - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        /* Additional styles for form consistency */
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .login-container h2 {
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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            height: 40px;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
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
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    
    <div class="login-container">
        <h2>Login</h2>
        
        <!-- Display the message if it exists -->
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form action="../../controllers/AuthController.php?action=login" method="POST" class="validate-form">
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
        
        <!--<p class="login-link">
            Don't have an account? <a href="http://localhost/uniexam/views/auth/register.php">Register here</a>.
        </p>-->
    </div>

    <?php require_once '../partials/footer.php'; ?>
    <script src="/js/validation.js"></script>
    <script>
    // Function to remove message after 5 seconds
    window.onload = function() {
        setTimeout(function() {
            var messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.classList.add('fade-out');
                // Remove the message from the DOM after the fade-out transition
                setTimeout(function() {
                    message.remove();
                }, 500); // Match this to the CSS transition duration
            });
        }, 5000); // Display the message for 5 seconds
    };
    </script>
</body>
</html>
