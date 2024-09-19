<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniExam | Your Exam Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('views/partials/header.php'); ?>
    
    <div class="container">
        <h1>Welcome to UniExam</h1>
        <p>UniExam is a comprehensive exam management system designed to simplify and streamline the exam process for educational institutions. From scheduling exams to automated grading and result analysis, UniExam has everything you need to manage your exams effectively.</p>

        <a href="views/students/register.php" class="cta-button">Get Started</a>

        <div class="features">
            <div class="feature">
                <h3>Efficient Exam Management</h3>
                <p>Schedule and manage exams with ease. Our system ensures that students and faculty stay informed and organized.</p>
            </div>
            <div class="feature">
                <h3>Automated Grading</h3>
                <p>Save time with our automated grading system that delivers accurate results in seconds, ensuring fairness and transparency.</p>
            </div>
            <div class="feature">
                <h3>Detailed Performance Reports</h3>
                <p>Generate insightful reports on student performance, making it easy to identify trends and areas for improvement.</p>
            </div>
            <div class="feature">
                <h3>Secure Data Management</h3>
                <p>Keep student information and exam results safe with our advanced security protocols, ensuring data integrity and privacy.</p>
            </div>
        </div>
    </div>

    <?php include('views/partials/footer.php'); ?>
</body>
</html>
