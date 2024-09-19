<?php 
require_once '../partials/header.php';
require_once __DIR__ . '/../../controllers/LecController.php';

$lecturerController = new LecturerController($pdo);
$courses = $lecturerController->getCourses();
?>
<div class="container">
    <h2>Add Lecturer</h2>
    <form action="../../controllers/LecController.php?action=addLecturer" method="POST" class="validate-form">
        <div class="form-group">
            <label for="lecturer_number">Lecturer Number:</label>
            <input type="text" id="lecturer_number" name="lecturer_number" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="course_id">Department:</label>
            <select id="course_id" name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>"><?= $course['department_name'] ?> (<?= $course['course_name'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Add Lecturer</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
