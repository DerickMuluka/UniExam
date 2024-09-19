<?php 
require_once '../partials/header.php'; 
require_once __DIR__ . '/../../config/db.php';

// Fetch all courses for the dropdown
$stmt = $pdo->query('SELECT id, course_name FROM courses');
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Add Student</h2>
    <form action="../../controllers/StudentController.php?action=addStudent" method="POST" class="validate-form">
        <div class="form-group">
            <label for="registration_number">Registration Number:</label>
            <input type="text" id="registration_number" name="registration_number" required>
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
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="course_id">Course Name:</label>
            <select id="course_id" name="course_id" required>
                <option value="" disabled selected>Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>

<style>
    /* Consistent styles from manage_students.php */
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .btn-primary {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }
</style>

<script src="/js/validation.js"></script>
