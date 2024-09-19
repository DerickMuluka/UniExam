<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<header>
    <h1>UniExam</h1>
    <nav>
        <ul>
            <li><a href="http://localhost/uniexam/index.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php'){echo 'active';} ?>">Home</a></li>
            <li><a href="http://localhost/uniexam/views/students/login.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'login.php' && strpos($_SERVER['REQUEST_URI'], 'students')){echo 'active';} ?>">Students</a></li>
            <li><a href="http://localhost/uniexam/views/lecturers/login.php" class="<?php if(basename($_SERVER['PHP_SELF']) == 'login.php' && strpos($_SERVER['REQUEST_URI'], 'lecturers')){echo 'active';} ?>">Lecturers</a></li>
            <li><a href="http://localhost/uniexam/views/auth/login.php?action=logout" class="<?php if(isset($_GET['action']) && $_GET['action'] == 'logout'){echo 'active';} ?>">Admin</a></li>
        </ul>
    </nav>
</header>
</body>
</html>
