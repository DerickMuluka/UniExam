<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['lecturer'])) {
    header('Location: login.php');
    exit;
}

$lecturer = $_SESSION['lecturer'];

// Fetch departments for the dropdown list
$stmt = $pdo->query('SELECT id, department_name FROM departments');
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $department_id = $_POST['department'] ?? '';

    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: edit_profile.php');
        exit;
    }

    $stmt = $pdo->prepare('UPDATE lecturers SET name = :name, email = :email, course_id = :course_id WHERE id = :id');
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'course_id' => $department_id,
        'id' => $lecturer['id']
    ]);

    // Update session with new lecturer details
    $_SESSION['lecturer']['name'] = $name;
    $_SESSION['lecturer']['email'] = $email;
    $_SESSION['lecturer']['course_id'] = $department_id;
    $_SESSION['success'] = 'Profile updated successfully!';
    header('Location: lecturer_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .edit-profile-container {
            margin-left: 20%;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-top: 30px;
        }
        h2 { text-align: left; color: #333; }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .btn:hover { background-color: #0056b3; }
        .error-message {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="edit_profile.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($lecturer['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($lecturer['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <select name="department" required>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['id']; ?>" <?= $department['id'] == $lecturer['course_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>

    <?php require_once '../partials/footer.php'; ?>
    
    <script>
        // Apply the message fade-out script
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
</body>
</html>
