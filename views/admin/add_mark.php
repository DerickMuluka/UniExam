<?php
// Corrected path to the db.php file
require_once '../../config/db.php';
require_once '../partials/header.php';
?>

<div class="container">
    <h2>Add Mark</h2>
    <form action="../../controllers/MarkController.php?action=addMark" method="POST" class="validate-form">
    <div class="form-group">
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required>
    </div>

    <div class="form-group">
        <label for="course_code">Course Code:</label>
        <select id="course_code" name="course_code" required>
            <?php
            // Fetch course codes from the database
            $stmt = $pdo->query('SELECT course_code FROM courses');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['course_code']}'>{$row['course_code']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="semester_code">Semester Code:</label>
        <select id="semester_code" name="semester_code" required>
            <?php
            // Fetch semester codes from the database
            $stmt = $pdo->query('SELECT semester_code FROM semesters');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['semester_code']}'>{$row['semester_code']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="unit_code">Unit Code:</label>
        <select id="unit_code" name="unit_code" required onchange="populateUnitDetails(this.value);">
            <?php
            // Fetch unit codes from the database
            $stmt = $pdo->query('SELECT id, unit_code FROM course_units');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id']}'>{$row['unit_code']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="lecturer_number">Lecturer Number:</label>
        <input type="text" id="lecturer_number" name="lecturer_number" required>
    </div>

    <div class="form-group">
        <label for="marks">Marks:</label>
        <input type="number" id="marks" name="marks" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Mark</button>
</form>

</div>

<script>
    function populateUnitDetails(unitCode) {
        fetch(`../../controllers/MarkController.php?action=getUnitDetails&unit_code=${unitCode}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.lecturer_number) {
                    document.getElementById('lecturer_number').value = data.lecturer_number;
                }
            })
            .catch(error => console.error('Error fetching unit details:', error));
    }
</script>

<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
