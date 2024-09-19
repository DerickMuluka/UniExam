<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['student'])) {
    $_SESSION['error'] = 'You must be logged in to access the dashboard.';
    header('Location: http://localhost/uniexam/views/students/login.php');
    exit;
}

$student = $_SESSION['student'];
$student_id = $student['id'];
$registration_number = $student['registration_number'];

try {
    // Fetch course name from the database
    $course_id = $student['course_id'];
    $query = "SELECT course_name FROM courses WHERE id = :course_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    $course_name = $course['course_name'];

    // Fetch registered semesters
    $query = "SELECT semester_name FROM semesters 
              INNER JOIN student_semesters ON semesters.id = student_semesters.semester_id 
              WHERE student_semesters.student_id = :student_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $registered_semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        .dashboard-container {
            margin-left: 20%;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-top: 30px;
        }
        h2 { text-align: left; color: #333; }
        .dashboard-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .dashboard-links .btn {
            width: 30%;
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
        .student-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .student-details h3 { margin-bottom: 15px; color: #333; }
        .student-details p { margin: 5px 0; font-size: 16px; color: #555; }
        .btn-logout { background-color: #dc3545; }
        .btn-logout:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?></h2>
        <div class="dashboard-links">
            <a href="view_results.php" class="btn">View Results</a>
            <a href="register_semester.php" class="btn">Register Semester</a>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </div>
        <div class="student-details">
            <h3>Student Details</h3>
            <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($registration_number); ?></p>
            <p><strong>Course:</strong> <?php echo htmlspecialchars($course_name); ?></p>
            <p><strong>Registered Semester(s):</strong> 
                <?php
                if (!empty($registered_semesters)) {
                    foreach ($registered_semesters as $semester) {
                        echo htmlspecialchars($semester['semester_name']) . "<br>";
                    }
                } else {
                    echo "No semester registered.";
                }
                ?>
            </p>
        </div>
        <a href="login.php" class="btn btn-logout">Logout</a>
    </div>
    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
