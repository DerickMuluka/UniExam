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

// Fetch the current marks
$marksQuery = "
    SELECT marks 
    FROM marks 
    WHERE registration_number = :registration_number 
      AND course_unit_id = :course_unit_id
";
$marksStmt = $pdo->prepare($marksQuery);
$marksStmt->bindParam(':registration_number', $registration_number);
$marksStmt->bindParam(':course_unit_id', $course_unit_id);
$marksStmt->execute();
$currentMarks = $marksStmt->fetch(PDO::FETCH_ASSOC);

if (!$currentMarks) {
    die('No marks found for the specified student and course unit.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marks = $_POST['marks'];

    // Update marks in the database
    $updateQuery = "
        UPDATE marks 
        SET marks = :marks 
        WHERE registration_number = :registration_number 
          AND course_unit_id = :course_unit_id
    ";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':marks', $marks);
    $updateStmt->bindParam(':registration_number', $registration_number);
    $updateStmt->bindParam(':course_unit_id', $course_unit_id);

    try {
        if ($updateStmt->execute()) {
            $_SESSION['success'] = 'Marks updated successfully.';
            header('Location: enter_marks.php');
            exit;
        } else {
            $error = 'Failed to update marks. Please try again.';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Marks - UniExam</title>
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
        <h2>Edit Marks</h2>
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <label for="marks">Edit Marks:</label>
                <input type="number" id="marks" name="marks" required min="0" max="100" value="<?= htmlspecialchars($currentMarks['marks']); ?>">
                <button type="submit">Update Marks</button>
            </form>
        </div>
    </div>

    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
