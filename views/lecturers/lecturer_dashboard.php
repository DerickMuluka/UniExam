<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['lecturer'])) {
    header('Location: login.php');
    exit;
}

$lecturer = $_SESSION['lecturer'];
$lecturer_id = $lecturer['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .dashboard-container {
            margin-left: 10%;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin-top: 30px;
        }
        h2 { text-align: left; color: #333; }
        .dashboard-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        .dashboard-links .btn {
            flex: 1;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .dashboard-links .btn:hover { background-color: #0056b3; }
        .btn-logout { background-color: #dc3545; }
        .btn-logout:hover { background-color: #c82333; }
        .btn-enter-marks { background-color: #28a745; }
        .btn-enter-marks:hover { background-color: #218838; }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    
    <div class="dashboard-container">
        <h2>Welcome, <?= htmlspecialchars($lecturer['name']); ?></h2>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message message"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="dashboard-links">
            <a href="view_classes.php" class="btn">View Classes</a>
            <a href="enter_marks.php" class="btn btn-enter-marks">Enter Marks</a>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </div>
        <a href="login.php" class="btn btn-logout">Logout</a>
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
