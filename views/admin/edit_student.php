<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/StudentController.php';

$studentController = new StudentController($pdo);

if (isset($_GET['id'])) {
    $student = $studentController->getStudentById($_GET['id']);
}

// Fetch all courses for the dropdown
$stmt = $pdo->query('SELECT id, course_name FROM courses');
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Edit Student</h2>
    <form action="../../controllers/StudentController.php?action=editStudent&id=<?php echo $student['id']; ?>" method="POST">
        <div class="form-group">
            <label for="registration_number">Registration Number:</label>
            <input type="text" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($student['registration_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="course_id">Course Name:</label>
            <select id="course_id" name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" 
                        <?php 
                        if (isset($student['course_id']) && $course['id'] == $student['course_id']) {
                            echo 'selected';
                        } 
                        ?>>
                        <?php echo htmlspecialchars($course['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Student</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
