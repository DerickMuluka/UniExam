<?php
require_once '../partials/header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/CourseController.php';
session_start();

$courseController = new CourseController($pdo);
$departments = $courseController->getAllDepartments();
?>
<div class="container">
    <h2>Add Course</h2>
    <?php
    // Display success or error messages
    if (isset($_SESSION['success_message'])) {
        echo '<div class="message success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    <form action="../../controllers/CourseController.php?action=addCourse" method="POST" class="validate-form">
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <input type="text" id="course_code" name="course_code" required>
        </div>
        <div class="form-group">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>
        </div>
        <div class="form-group">
            <label for="department_id">Department:</label>
            <select id="department_id" name="department_id" required>
                <?php foreach ($departments as $department): ?>
                    <option value="<?php echo htmlspecialchars($department['id']); ?>">
                        <?php echo htmlspecialchars($department['department_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
