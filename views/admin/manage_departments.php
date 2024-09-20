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
    <h2>Manage Departments</h2>
    <a href="add_department.php" class="btn btn-primary add-department-btn">Add New Department</a>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Head of Department</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once __DIR__ . '/../../config/db.php';
            require_once __DIR__ . '/../../models/Department.php';

            $departmentModel = new Department($pdo);
            $departments = $departmentModel->getAll();

            foreach ($departments as $department) {
                echo "<tr>";
                echo "<td>{$department['department_name']}</td>";
                echo "<td>{$department['head_of_department']}</td>";
                echo "<td>{$department['location']}</td>";
                echo "<td>
                        <a href='edit_department.php?id={$department['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='#' onclick='confirmDelete({$department['id']})' class='btn btn-danger btn-sm'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php require_once '../partials/footer.php'; ?>

<!-- Include JavaScript for message styling effect -->
<script>
    // Function to remove message after 5 seconds
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

    function confirmDelete(departmentId) {
        if (confirm("Are you sure you want to delete this department?")) {
            window.location.href = '../../controllers/DepartmentController.php?action=delete&id=' + departmentId;
        }
    }
</script>

<!-- Additional CSS for button styling and layout adjustment -->
<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }

    .add-department-btn {
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

    .add-department-btn:hover {
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
        padding: 5px 10px;
        font-size: 14px;
        border-radius: 3px;
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

    .btn-action {
        margin-right: 0.5rem;
        display: inline-block;
        text-align: center;
    }

    .btn-action:last-child {
        margin-right: 0;
    }

    .btn-group-wrapper {
        display: flex;
        gap: 0.5rem;
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

    @media (max-width: 768px) {
        .table th, .table td {
            padding: 8px 10px;
            font-size: 16px;
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 8px;
        }

        .add-department-btn {
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
            padding: 4px 6px;
        }

        .add-department-btn {
            padding: 10px;
            font-size: 16px;
        }
    }
</style>
