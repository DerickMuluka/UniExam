<?php require_once '../partials/header.php'; ?>
<?php 
require_once __DIR__ . '/../../config/db.php'; // Ensure correct path to db.php
require_once __DIR__ . '/../../controllers/UnitController.php';

if (isset($_GET['id'])) {
    $unitController = new UnitController($pdo);
    $unit = $unitController->getUnitById($_GET['id']); // Fetch the specific unit based on ID
    $courseCodes = $unitController->getCourseCodes(); // Fetch all course codes
    $semesterCodes = $unitController->getSemesterCodes(); // Fetch all semester codes
} else {
    header('Location: manage_units.php');
    exit();
}
?>

<div class="container">
    <h2>Edit Unit</h2>
    
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

    <form action="../../controllers/UnitController.php?action=editUnit&id=<?= htmlspecialchars($unit['id']) ?>" method="POST">
        <div class="form-group">
            <label for="course_code">Course Code</label>
            <select name="course_code" class="form-control" id="course_code" required>
                <?php foreach ($courseCodes as $course): ?>
                    <option value="<?= htmlspecialchars($course['course_code']) ?>" <?= $course['course_code'] == $unit['course_code'] ? 'selected' : '' ?>><?= htmlspecialchars($course['course_code']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit_code">Unit Code</label>
            <input type="text" name="unit_code" class="form-control" id="unit_code" value="<?= htmlspecialchars($unit['unit_code']) ?>" required>
        </div>
        <div class="form-group">
            <label for="unit_name">Unit Name</label>
            <input type="text" name="unit_name" class="form-control" id="unit_name" value="<?= htmlspecialchars($unit['unit_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="semester_code">Semester Code</label>
            <select name="semester_code" class="form-control" id="semester_code" required>
                <?php foreach ($semesterCodes as $semester): ?>
                    <option value="<?= htmlspecialchars($semester['semester_code']) ?>" <?= $semester['semester_code'] == $unit['semester_code'] ? 'selected' : '' ?>><?= htmlspecialchars($semester['semester_code']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!--<div class="form-group">
            <label for="academic_year">Academic Year</label>
            <input type="text" name="academic_year" class="form-control" id="academic_year" value="<?= htmlspecialchars($unit['academic_year']) ?>" required>
        </div>-->
        <button type="submit" class="btn btn-primary">Update Unit</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
