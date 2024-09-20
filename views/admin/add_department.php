<?php require_once '../partials/header.php'; ?>
<div class="container">
    <h2>Add Department</h2>
    <form action="../../controllers/DepartmentController.php?action=addDepartment" method="POST" class="validate-form">
        <div class="form-group">
            <label for="department_name">Department Name:</label>
            <input type="text" id="department_name" name="department_name" required>
        </div>
        <div class="form-group">
            <label for="head_of_department">Head of Department:</label>
            <input type="text" id="head_of_department" name="head_of_department" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
        </div>
        <button type="submit">Add Department</button>
    </form>
</div>
<?php require_once '../partials/footer.php'; ?>
<script src="/js/validation.js"></script>
