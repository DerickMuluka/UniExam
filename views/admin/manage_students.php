<?php 
require_once '../partials/header.php';
session_start(); // Start session to handle flash messages

// Display success or error messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="message success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>
<div class="container">
    <h2>Manage Students</h2>
    <a href="add_student.php" class="btn btn-primary add-student-btn">Add New Student</a>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Registration Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course Name</th> <!-- Updated column name -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once __DIR__ . '/../../config/db.php';
            require_once __DIR__ . '/../../models/Student.php';

            $studentModel = new Student($pdo);
            $students = $studentModel->getAll();

            foreach ($students as $student) {
                echo "<tr>";
                echo "<td>{$student['registration_number']}</td>";
                echo "<td>{$student['name']}</td>";
                echo "<td>{$student['email']}</td>";
                echo "<td>{$student['course_name']}</td>"; // Display course_name instead of course_id
                echo "<td class='actions'>
                        <a href='edit_student.php?id={$student['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='../../controllers/StudentController.php?action=deleteStudent&id={$student['id']}' 
                           class='btn btn-danger btn-sm' 
                           onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php require_once '../partials/footer.php'; ?>


<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }

    .add-student-btn {
        margin-bottom: 15px;
        background-color: #3498db; 
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .add-student-btn:hover {
        background-color: #2980b9; 
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 18px;
        text-align: left;
    }

    .table th, .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: blueviolet;
        font-weight: bold;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .actions {
        display: flex;
        gap: 5px; /* Space between buttons */
        justify-content: flex-start; /* Align buttons to the left */
    }

    .btn-sm {
        padding: 4px 8px; /* Smaller padding */
        font-size: 12px; /* Smaller font size */
        border-radius: 3px;
    }

    .btn-warning {
        background-color: #ff9800; /* Orange */
        border: none;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e68900; /* Darker orange */
    }

    .btn-danger {
        background-color: #f44336; /* Red */
        border: none;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #d32f2f; /* Darker red */
    }

    @media (max-width: 768px) {
        .table th, .table td {
            padding: 8px 10px;
            font-size: 16px;
        }

        .btn-sm {
            font-size: 10px;
            padding: 3px 6px;
        }

        .add-student-btn {
            width: 100%;
            margin-bottom: 20px;
            padding: 12px;
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .table th, .table td {
            padding: 6px 8px;
            font-size: 14px;
        }

        .btn-sm {
            font-size: 10px;
            padding: 3px 5px;
        }

        .add-student-btn {
            padding: 10px;
            font-size: 16px;
        }
    }

    .message {
        margin: 20px 0;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
    }

    .message.success {
        background-color: #4CAF50;
        color: white;
    }

    .message.error {
        background-color: #f44336;
        color: white;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
</style>

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
