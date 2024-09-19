<?php
require_once '../partials/header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/CourseController.php';

$courseController = new CourseController($pdo);
$departments = $courseController->getAllDepartments();

if (isset($_GET['id'])) {
    $course = $courseController->getCourseById($_GET['id']);
}
?>
<div class="container">
    <h2>Edit Course</h2>
    <form action="../../controllers/CourseController.php?action=updateCourse&id=<?php echo htmlspecialchars($_GET['id']); ?>" method="POST" class="validate-form">
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <input type="text" id="course_code" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
        </div>
        <div class="form-group">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="department_id">Department:</label>
            <select id="department_id" name="department_id" required>
                <?php foreach ($departments as $department): ?>
                    <option value="<?php echo htmlspecialchars($department['id']); ?>" <?php echo ($course['department_id'] == $department['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($department['department_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Course</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
