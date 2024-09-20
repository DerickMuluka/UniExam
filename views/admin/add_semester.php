<?php require_once '../partials/header.php'; ?>
<div class="container">
    <h2>Add Semester</h2>
    <form action="../../controllers/SemesterController.php?action=addSemester" method="POST">
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <select id="course_code" name="course_code" required>
                <?php
                require_once __DIR__ . '/../../config/db.php';
                $stmt = $pdo->query('SELECT course_code FROM courses');
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($courses as $course) {
                    echo "<option value=\"{$course['course_code']}\">{$course['course_code']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="semester_code">Semester Code:</label>
            <input type="text" id="semester_code" name="semester_code" required>
        </div>
        <div class="form-group">
            <label for="semester_name">Semester Name:</label>
            <input type="text" id="semester_name" name="semester_name" required>
        </div>
        <div class="form-group">
            <label for="year_of_study">Year of Study:</label>
            <input type="number" id="year_of_study" name="year_of_study" required>
        </div>
        <!--<div class="form-group">
            <label for="academic_year">Academic Year:</label>
            <input type="text" id="academic_year" name="academic_year" required>
        </div>-->
        <button type="submit">Add Semester</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
