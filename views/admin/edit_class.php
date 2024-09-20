<?php 
require_once '../partials/header.php';  // Load header content
require_once '../../config/db.php';     // Database connection
session_start();  // Start the session

// Fetch class ID from URL
$class_id = $_GET['id'] ?? null;

if ($class_id) {
    // Fetch class details
    $stmt = $pdo->prepare("
        SELECT c.id, l.lecturer_number, l.name AS lecturer_name, 
               co.course_code, co.course_name, 
               s.semester_code, s.semester_name,
               cu.unit_code, cu.unit_name
        FROM classes c
        JOIN lecturers l ON c.lecturer_id = l.id
        JOIN semesters s ON c.semester_id = s.id
        JOIN courses co ON co.id = c.course_id
        JOIN course_units cu ON cu.course_code = co.course_code AND cu.semester_code = s.semester_code
        WHERE c.id = :id
    ");
    $stmt->execute(['id' => $class_id]);
    $class = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Link to your styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Class</h2>
        <form id="editClassForm" action="../../controllers/ClassController.php?action=editClass&id=<?php echo htmlspecialchars($class_id); ?>" method="POST">
            <!-- Lecturer Selection -->
            <div class="form-group">
                <label for="lecturer_id">Lecturer</label>
                <select id="lecturer_id" name="lecturer_id" required>
                    <option value="">Select Lecturer</option>
                    <?php
                    // Fetching lecturers data from the database
                    $lecturers = $pdo->query('SELECT lecturer_number, name FROM lecturers')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($lecturers as $lecturer) {
                        $selected = $lecturer['lecturer_number'] == $class['lecturer_number'] ? 'selected' : '';
                        echo "<option value='{$lecturer['lecturer_number']}' $selected>{$lecturer['name']} ({$lecturer['lecturer_number']})</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Department Selection -->
            <div class="form-group">
                <label for="department_id">Department</label>
                <select id="department_id" name="department_id" required>
                    <option value="">Select Department</option>
                    <?php
                    // Fetching departments data from the database
                    $departments = $pdo->query('SELECT id, department_name FROM departments')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($departments as $department) {
                        $selected = $department['id'] == $class['department_id'] ? 'selected' : '';
                        echo "<option value='{$department['id']}' $selected>{$department['department_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Course Selection -->
            <div class="form-group">
                <label for="course_id">Course</label>
                <select id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    <?php
                    // Fetching courses from the database
                    $courses = $pdo->query('SELECT course_code, course_name FROM courses')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($courses as $course) {
                        $selected = $course['course_code'] == $class['course_code'] ? 'selected' : '';
                        echo "<option value='{$course['course_code']}' $selected>{$course['course_code']} - {$course['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Semester Selection -->
            <div class="form-group">
                <label for="semester_id">Semester</label>
                <select id="semester_id" name="semester_id" required>
                    <option value="">Select Semester</option>
                    <?php
                    // Fetching semesters from the database
                    $semesters = $pdo->query('SELECT id, semester_code, semester_name FROM semesters')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($semesters as $semester) {
                        $selected = $semester['id'] == $class['semester_id'] ? 'selected' : '';
                        echo "<option value='{$semester['id']}' $selected>{$semester['semester_code']} - {$semester['semester_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Unit Selection -->
            <div class="form-group">
                <label for="unit_id">Unit</label>
                <select id="unit_id" name="unit_id" required>
                    <option value="">Select Unit</option>
                    <?php
                    // Fetching units from the database
                    $units = $pdo->query('SELECT id, unit_code, unit_name FROM course_units')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($units as $unit) {
                        $selected = $unit['id'] == $class['unit_id'] ? 'selected' : '';
                        echo "<option value='{$unit['id']}' $selected>{$unit['unit_code']} - {$unit['unit_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit">Update Class</button>
        </form>
    </div>
</body>
</html>

<?php require_once '../partials/footer.php'; ?>  <!-- Load footer content -->
