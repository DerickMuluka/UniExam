<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/MarkController.php';

if (!isset($_GET['id'])) {
    die('Invalid ID');
}

$markController = new MarkController($pdo);
$mark = $markController->getMarkById($_GET['id']);
if (!$mark) {
    die('Mark not found');
}
?>
<?php require_once '../partials/header.php'; ?>
<div class="container">
    <h2>Edit Mark</h2>
    <form action="../../controllers/MarkController.php?action=updateMark" method="POST" class="validate-form">
        <input type="hidden" name="id" value="<?php echo $mark['id']; ?>">
        <div class="form-group">
            <label for="registration_number">Registration Number:</label>
            <input type="text" id="registration_number" name="registration_number" value="<?php echo $mark['registration_number']; ?>" required>
        </div>
        <div class="form-group">
            <label for="course_code">Course Code:</label>
            <select id="course_code" name="course_code" required>
                <?php
                // Fetch course codes from the database
                $stmt = $pdo->query('SELECT course_code FROM courses');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = $mark['course_code'] == $row['course_code'] ? 'selected' : '';
                    echo "<option value='{$row['course_code']}' $selected>{$row['course_code']}</option>";
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
                    $selected = $mark['semester_code'] == $row['semester_code'] ? 'selected' : '';
                    echo "<option value='{$row['semester_code']}' $selected>{$row['semester_code']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit_code">Unit Code:</label>
            <select id="unit_code" name="unit_code" required>
    <?php
    // Fetch all valid course units from the course_units table
    $stmt = $pdo->query("SELECT id, unit_name FROM course_units");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value=\"{$row['id']}\" " . ($mark['course_unit_id'] == $row['id'] ? "selected" : "") . ">{$row['unit_name']}</option>";
    }
    ?>
</select>

        </div>
        <div class="form-group">
            <label for="lecturer_number">Lecturer Number:</label>
            <input type="text" id="lecturer_number" name="lecturer_number" value="<?php echo $mark['lecturer_number']; ?>" required>
        </div>
        <div class="form-group">
            <label for="marks">Marks:</label>
            <input type="number" id="marks" name="marks" value="<?php echo $mark['marks']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Mark</button>
    </form>
</div>

<script>
    function populateUnitDetails(unitCode) {
        fetch(`../../controllers/MarkController.php?action=getUnitDetails&unit_code=${unitCode}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('lecturer_number').value = data.lecturer_number;
            });
    }
</script>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>

<script>
    // Function to remove message after 5 seconds
    window.onload = function() {
        setTimeout(function() {
            var messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.classList.add('fade-out');
                // Remove the message from the DOM after the fade-out transition
                setTimeout(function() {
                    message.remove();
                }, 500); // Match this to the CSS transition duration
            });
        }, 5000); // Display the message for 5 seconds
    };
</script>