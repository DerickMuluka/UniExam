<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['lecturer'])) {
    header('Location: login.php');
    exit;
}

$lecturer = $_SESSION['lecturer'];
$lecturer_id = $lecturer['id'];
$registration_number = $_GET['registration_number'] ?? null;
$course_unit_id = $_GET['course_unit_id'] ?? null;

if (!$registration_number || !$course_unit_id) {
    die('Invalid registration number or course unit ID.');
}

// Fetch the course_code based on course_unit_id
$courseCodeQuery = "SELECT course_code, semester_code FROM course_units WHERE id = :course_unit_id";
$courseCodeStmt = $pdo->prepare($courseCodeQuery);
$courseCodeStmt->bindParam(':course_unit_id', $course_unit_id);
$courseCodeStmt->execute();
$courseUnit = $courseCodeStmt->fetch(PDO::FETCH_ASSOC);

if (!$courseUnit) {
    die('Invalid course unit ID.');
}

$courseCode = $courseUnit['course_code'];
$semesterCode = $courseUnit['semester_code'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marks = $_POST['marks'];

    // Validate the course_unit_id existence
    $checkQuery = "SELECT id FROM course_units WHERE id = :course_unit_id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':course_unit_id', $course_unit_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        $error = 'Invalid course unit ID. Please select a valid course unit.';
    } else {
        // Check if the marks entry already exists to prevent duplicate entries
        $duplicateCheckQuery = "
            SELECT id 
            FROM marks 
            WHERE registration_number = :registration_number 
              AND course_unit_id = :course_unit_id
        ";
        $duplicateCheckStmt = $pdo->prepare($duplicateCheckQuery);
        $duplicateCheckStmt->bindParam(':registration_number', $registration_number);
        $duplicateCheckStmt->bindParam(':course_unit_id', $course_unit_id);
        $duplicateCheckStmt->execute();

        if ($duplicateCheckStmt->rowCount() > 0) {
            $error = 'Marks for this student and course unit already exist.';
        } else {
            // Insert marks into the database
            $query = "
                INSERT INTO marks (
                    registration_number, 
                    course_unit_id, 
                    course_code, 
                    semester_code, 
                    lecturer_number, 
                    marks
                ) VALUES (
                    :registration_number, 
                    :course_unit_id, 
                    :course_code, 
                    :semester_code, 
                    :lecturer_number, 
                    :marks
                )
            ";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':registration_number', $registration_number);
            $stmt->bindParam(':course_unit_id', $course_unit_id);
            $stmt->bindParam(':course_code', $courseCode);
            $stmt->bindParam(':semester_code', $semesterCode);
            $stmt->bindParam(':lecturer_number', $lecturer['lecturer_number']);
            $stmt->bindParam(':marks', $marks);

            try {
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Marks added successfully.';
                    header('Location: enter_marks.php');
                    exit;
                } else {
                    $error = 'Failed to add marks. Please try again.';
                }
            } catch (PDOException $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Marks - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px; /* Added space below the header */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 1rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="dashboard-container">
        <h2>Add Marks</h2>
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <label for="marks">Enter Marks:</label>
                <input type="number" id="marks" name="marks" required min="0" max="100">
                <button type="submit">Add Marks</button>
            </form>
        </div>
    </div>

    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
