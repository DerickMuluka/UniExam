<?php require_once '../partials/header.php'; ?>
<div class="container">
    <h2>Edit Department</h2>
    <?php
    require_once __DIR__ . '/../../config/db.php';
    require_once __DIR__ . '/../../models/Department.php';

    if (isset($_GET['id'])) {
        $departmentModel = new Department($pdo);
        $department = $departmentModel->getById($_GET['id']);

        if ($department) {
            ?>
            <form action="../../controllers/DepartmentController.php?action=updateDepartment" method="POST" class="validate-form">
                <input type="hidden" name="id" value="<?php echo $department['id']; ?>">
                <div class="form-group">
                    <label for="department_name">Department Name:</label>
                    <input type="text" id="department_name" name="department_name" value="<?php echo $department['department_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="head_of_department">Head of Department:</label>
                    <input type="text" id="head_of_department" name="head_of_department" value="<?php echo $department['head_of_department']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo $department['location']; ?>" required>
                </div>
                <button type="submit">Update Department</button>
            </form>
            <?php
        } else {
            echo "<p>Department not found.</p>";
        }
    } else {
        echo "<p>No department ID provided.</p>";
    }
    ?>
</div>
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


