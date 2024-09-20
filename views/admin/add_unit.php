<?php require_once '../partials/header.php'; ?>
<?php 
require_once __DIR__ . '/../../config/db.php'; // Ensure correct path to db.php
require_once __DIR__ . '/../../controllers/UnitController.php';

$unitController = new UnitController($pdo);

// Fetch course codes and semester codes
$courseCodes = $unitController->getCourseCodes();
$semesterCodes = $unitController->getSemesterCodes();
?>

<div class="container">
    <h2>Add New Unit</h2>
    
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <form action="../../controllers/UnitController.php?action=addUnit" method="POST">
        <div class="form-group">
            <label for="course_code">Course Code</label>
            <select name="course_code" class="form-control" id="course_code" required>
                <?php foreach ($courseCodes as $course): ?>
                    <option value="<?= htmlspecialchars($course['course_code']) ?>"><?= htmlspecialchars($course['course_code']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit_code">Unit Code</label>
            <input type="text" name="unit_code" class="form-control" id="unit_code" required>
        </div>
        <div class="form-group">
            <label for="unit_name">Unit Name</label>
            <input type="text" name="unit_name" class="form-control" id="unit_name" required>
        </div>
        <div class="form-group">
            <label for="semester_code">Semester Code</label>
            <select name="semester_code" class="form-control" id="semester_code" required>
                <?php foreach ($semesterCodes as $semester): ?>
                    <option value="<?= htmlspecialchars($semester['semester_code']) ?>"><?= htmlspecialchars($semester['semester_code']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Unit</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
