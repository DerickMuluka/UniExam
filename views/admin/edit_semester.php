<?php
require_once '../partials/header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Semester.php';

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "No semester ID provided.";
    header('Location: manage_semesters.php');
    exit();
}

$semesterModel = new Semester($pdo);
$semester = $semesterModel->getById($_GET['id']);

if (!$semester) {
    $_SESSION['error_message'] = "Semester not found.";
    header('Location: manage_semesters.php');
    exit();
}
?>
<div class="container">
    <h2>Edit Semester</h2>
    <form action="../../controllers/SemesterController.php?action=updateSemester&id=<?php echo $semester['id']; ?>" method="POST">
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <select id="course_code" name="course_code" required>
                <?php
                $stmt = $pdo->query('SELECT course_code FROM courses');
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($courses as $course) {
                    $selected = ($course['course_code'] === $semester['course_code']) ? 'selected' : '';
                    echo "<option value=\"{$course['course_code']}\" $selected>{$course['course_code']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="semester_code">Semester Code:</label>
            <input type="text" id="semester_code" name="semester_code" value="<?php echo $semester['semester_code']; ?>" required>
        </div>
        <div class="form-group">
            <label for="semester_name">Semester Name:</label>
            <input type="text" id="semester_name" name="semester_name" value="<?php echo $semester['semester_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="year_of_study">Year of Study:</label>
            <input type="number" id="year_of_study" name="year_of_study" value="<?php echo $semester['year_of_study']; ?>" required>
        </div>
        <!--<div class="form-group">
            <label for="academic_year">Academic Year:</label>
            <input type="text" id="academic_year" name="academic_year" value="<?php echo $semester['academic_year']; ?>" required>
        </div>-->
        <button type="submit">Update Semester</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
