<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['student'])) {
    $_SESSION['error'] = 'You must be logged in to register for a semester and units.';
    header('Location: login.php');
    exit;
}

$student = $_SESSION['student'];
$student_id = $student['id'];
$course_id = $student['course_id'];

$semester_id = null;
$units = [];
$registered_units = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['semester_id']) && isset($_POST['register_semester'])) {
        $semester_id = $_POST['semester_id'];

        // Check if the student has already registered for this semester
        $query = "SELECT * FROM student_semesters WHERE student_id = :student_id AND semester_id = :semester_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $stmt->execute();
        $registration = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registration) {
            $_SESSION['error'] = 'You have already registered for this semester.';
        } else {
            // Register the student for the selected semester
            $query = "INSERT INTO student_semesters (student_id, semester_id) VALUES (:student_id, :semester_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Semester registration successful.';

                // Fetch units for the selected semester based on the updated schema
                $query = "
                    SELECT u.id, cu.unit_code, cu.unit_name, s.semester_name, u.lecturer_id
                    FROM course_units cu
                    JOIN units u ON cu.id = u.course_unit_id
                    JOIN semesters s ON cu.semester_code = s.semester_code
                    WHERE u.course_id = :course_id
                    AND s.id = :semester_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
                $stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
                $stmt->execute();
                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch already registered units for the student
                $registered_units_query = "
                    SELECT unit_id 
                    FROM student_unit_registrations 
                    WHERE student_id = :student_id";
                $stmt = $pdo->prepare($registered_units_query);
                $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt->execute();
                $registered_units = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            } else {
                $_SESSION['error'] = 'Failed to register for the semester. Please try again.';
            }
        }
    } elseif (isset($_POST['units']) && isset($_POST['semester_id'])) {
        // Handle unit registration confirmation
        $semester_id = $_POST['semester_id'];

        foreach ($_POST['units'] as $unit_id) {
            // Prevent duplicate unit registration
            if (!in_array($unit_id, $registered_units)) {
                // Fetch the correct course_unit_id for the given unit
                $query = "SELECT cu.id as course_unit_id, u.lecturer_id FROM course_units cu 
                          JOIN units u ON cu.id = u.course_unit_id WHERE u.id = :unit_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
                $stmt->execute();
                $course_unit = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($course_unit) {
                    $course_unit_id = $course_unit['course_unit_id'];
                    $lecturer_id = $course_unit['lecturer_id'];

                    // Insert using the correct course_unit_id
                    $query = "INSERT INTO student_unit_registrations (student_id, unit_id, lecturer_id) 
                              VALUES (:student_id, :unit_id, :lecturer_id)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                    $stmt->bindParam(':unit_id', $course_unit_id, PDO::PARAM_INT);
                    $stmt->bindParam(':lecturer_id', $lecturer_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }

        $_SESSION['success'] = 'All units have been registered successfully.';
        header('Location: student_dashboard.php');
        exit;
    }
}

// Fetch semesters for the dropdown based on the student's course
$query = "
    SELECT s.id, s.semester_name 
    FROM semesters s 
    JOIN courses c ON c.course_code = s.course_code 
    WHERE c.id = :course_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
$stmt->execute();
$semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Semester and Units - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        .register-semester-units-container {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 30px auto;
        }
        h2, h3 { text-align: center; color: #333; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px; color: #333; }
        .form-group select, .form-group input[type="checkbox"] { width: 100%; padding: 10px; font-size: 14px; }
        .form-group input[type="checkbox"] { margin-right: 10px; }
        .message { padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .message.success { background-color: #d4edda; color: #155724; }
        .message.error { background-color: #f8d7da; color: #721c24; }
        .btn { width: 100%; padding: 12px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; }
        .btn:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .fade-out { opacity: 0; transition: opacity 0.5s ease-out; }
    </style>
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
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    <div class="register-semester-units-container">
        <h2>Register for Semester and Units</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="semester">Select Semester:</label>
                <select name="semester_id" id="semester" required>
                    <option value="">-- Select Semester --</option>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo $semester['id']; ?>">
                            <?php echo $semester['semester_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="register_semester" class="btn">Register Semester</button>
        </form>
        
        <?php if ($units): ?>
            <h3>Select Units to Register</h3>
            <form method="POST">
                <input type="hidden" name="semester_id" value="<?php echo $semester_id; ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Unit Code</th>
                            <th>Unit Name</th>
                            <th>Lecturer</th>
                            <th>Register</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($units as $unit): ?>
                            <tr>
                                <td><?php echo $unit['unit_code']; ?></td>
                                <td><?php echo $unit['unit_name']; ?></td>
                                <td><?php echo $unit['lecturer_id']; ?></td>
                                <td>
                                    <input type="checkbox" name="units[]" value="<?php echo $unit['id']; ?>" 
                                    <?php echo in_array($unit['id'], $registered_units) ? 'checked disabled' : ''; ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn">Register Selected Units</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
