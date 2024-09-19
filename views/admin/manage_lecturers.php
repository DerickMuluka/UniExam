<?php
require_once '../partials/header.php';
session_start();

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
    <h2>Manage Lecturers</h2>
    <a href="add_lecturer.php" class="btn btn-primary add-lecturer-btn">Add New Lecturer</a>
    <table class="table">
        <thead>
            <tr>
                <th>Lecturer Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once __DIR__ . '/../../config/db.php';
            require_once __DIR__ . '/../../controllers/LecController.php';

            $lecturerController = new LecturerController($pdo);
            $lecturers = $lecturerController->getAllLecturers();

            foreach ($lecturers as $lecturer) {
                echo "<tr>";
                echo "<td>{$lecturer['lecturer_number']}</td>";
                echo "<td>{$lecturer['name']}</td>";
                echo "<td>{$lecturer['email']}</td>";
                echo "<td>{$lecturer['department_name']}</td>";
                echo "<td>{$lecturer['course_name']}</td>";
                echo "<td>
                        <div class='action-buttons'>
                            <a href='edit_lecturer.php?id={$lecturer['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='../../controllers/LecController.php?action=deleteLecturer&id={$lecturer['id']}' 
                               class='btn btn-danger btn-sm' 
                               onclick=\"return confirm('Are you sure you want to delete this lecturer?');\">Delete</a>
                        </div>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>

<script>
    window.onload = function() {
        setTimeout(function() {
            var messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.classList.add('fade-out');
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    };
</script>

<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }

    .add-lecturer-btn {
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

    .add-lecturer-btn:hover {
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

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 3px;
        margin-right: 5px;
    }

    .btn-warning {
        background-color: #ff9800;
        border: none;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e68900;
    }

    .btn-danger {
        background-color: #f44336;
        border: none;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    @media (max-width: 768px) {
        .table th, .table td {
            padding: 8px 10px;
            font-size: 16px;
        }

        .btn-sm {
            font-size: 10px;
            padding: 4px 6px;
        }

        .add-lecturer-btn {
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
            font-size: 8px;
            padding: 2px 4px;
        }

        .add-lecturer-btn {
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
