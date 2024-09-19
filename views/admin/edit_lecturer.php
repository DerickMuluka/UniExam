<?php
require_once '../partials/header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/LecController.php';

$lecturerController = new LecturerController($pdo);
$courses = $lecturerController->getCourses();
$lecturer = null;

if (isset($_GET['id'])) {
    $lecturer = $lecturerController->getLecturerById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $lecturerController->updateLecturer($_GET['id'], $_POST);
}
?>

<div class="container">
    <h2>Edit Lecturer</h2>
    <form action="../../controllers/LecController.php?action=updateLecturer&id=<?= $_GET['id'] ?>" method="POST" class="validate-form">
        <div class="form-group">
            <label for="lecturer_number">Lecturer Number:</label>
            <input type="text" id="lecturer_number" name="lecturer_number" value="<?= $lecturer['lecturer_number'] ?>" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $lecturer['name'] ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $lecturer['email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="course_id">Department:</label>
            <select id="course_id" name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" <?= $course['id'] == $lecturer['course_id'] ? 'selected' : '' ?>><?= $course['department_name'] ?> (<?= $course['course_name'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Update Lecturer</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
