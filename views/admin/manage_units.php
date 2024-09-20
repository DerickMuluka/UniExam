<?php 
require_once '../partials/header.php'; 
require_once __DIR__ . '/../../controllers/UnitController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$unitController = new UnitController($pdo);

// Fetch all units
try {
    $units = $unitController->getAllUnits();
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Failed to retrieve units. Please try again.';
    header('Location: /UniExam/views/admin/manage_units.php');
    exit();
}

// Display success or error messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="container">
    <h2>Manage Units</h2>
    <a href="add_unit.php" class="btn btn-primary add-student-btn">Add New Unit</a>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                    <th>Semester Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($units)): ?>
                    <?php foreach ($units as $unit): ?>
                    <tr>
                        <td><?= htmlspecialchars($unit['course_code']) ?></td>
                        <td><?= htmlspecialchars($unit['unit_code']) ?></td>
                        <td><?= htmlspecialchars($unit['unit_name']) ?></td>
                        <td><?= htmlspecialchars($unit['semester_code']) ?></td>
                        <td class="actions">
                            <a href="edit_unit.php?id=<?= htmlspecialchars($unit['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../../controllers/UnitController.php?action=deleteUnit&id=<?= htmlspecialchars($unit['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this unit?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No units found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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
        gap: 10px;
        justify-content: center;
    }

    .btn-sm {
        padding: 4px 8px; /* Adjusted padding */
        font-size: 12px; /* Adjusted font size */
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

    @media (max-width: 768px) {
        .table th, .table td {
            padding: 10px 12px; /* Updated padding */
            font-size: 16px;
        }

        .btn-sm {
            font-size: 12px;
            padding: 4px 6px; /* Updated padding */
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
            padding: 8px 10px; /* Updated padding */
            font-size: 14px;
        }

        .btn-sm {
            font-size: 10px;
            padding: 4px 6px; /* Updated padding */
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
