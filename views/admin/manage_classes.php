<?php 
require_once '../partials/header.php';
require_once __DIR__ . '/../../controllers/ClassController.php';
session_start();

// Display success or error messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}

// Instantiate ClassController
$classController = new ClassController($pdo);
$classes = $classController->getAllClasses();
?>

<div class="container">
    <h2>Manage Classes</h2>
    <a href="add_class.php" class="btn btn-primary add-class-btn">Add New Class</a>

    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Lecturer Number</th>
                <th>Name</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Semester Code</th>
                <th>Unit Code</th>
                <th>Unit Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?php echo htmlspecialchars($class['lecturer_number']); ?></td>
                    <td><?php echo htmlspecialchars($class['lecturer_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($class['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['semester_code']); ?></td>
                    <td><?php echo htmlspecialchars($class['unit_code']); ?></td>
                    <td><?php echo htmlspecialchars($class['unit_name']); ?></td>
                    <td>
                        <div class='btn-group-wrapper'>
                            <a href="edit_class.php?id=<?php echo htmlspecialchars($class['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="../../controllers/ClassController.php?action=deleteClass&id=<?php echo htmlspecialchars($class['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>


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

<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }

    .add-class-btn {
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

    .add-class-btn:hover {
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
        color: white;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-group-wrapper {
        display: flex;
        gap: 5px; /* Space between buttons */
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
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

        .add-class-btn {
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
            padding: 2px 5px;
        }

        .add-class-btn {
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

    header {
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    /* Adjust container to ensure it doesn't overlap with the fixed header */
    .container {
        padding-top: 60px; /* Adjust based on header height */
    }
</style>
