<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['student'])) {
    $_SESSION['error'] = 'You must be logged in to edit your profile.';
    header('Location: http://localhost/uniexam/views/students/login.php');
    exit;
}

$student = $_SESSION['student'];
$student_id = $student['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetching the form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email is required.';
    }
    if (!empty($password) && $password !== $confirm_password) {
        $errors['password'] = 'Passwords do not match.';
    }

    // If no errors, update the student profile
    if (empty($errors)) {
        try {
            $query = "UPDATE students SET name = :name, email = :email";
            
            // If the password is provided, add it to the query
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $query .= ", password = :password";
            }

            $query .= " WHERE id = :student_id";
            $stmt = $pdo->prepare($query);

            // Bind parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                // Update session data
                $_SESSION['student']['name'] = $name;
                $_SESSION['student']['email'] = $email;

                $_SESSION['success'] = 'Profile updated successfully.';
                header('Location: student_dashboard.php');
                exit;
            } else {
                $errors['general'] = 'Failed to update profile. Please try again.';
            }
        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - UniExam</title>
    <link rel="stylesheet" href="/css/styles.css">
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
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 10px; text-align: left; font-weight: bold; font-size: 14px; color: #333; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 16px; }
        .message { padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .message.success { background-color: #d4edda; color: #155724; }
        .message.error { background-color: #f8d7da; color: #721c24; }
        .btn { width: 100%; padding: 12px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; }
        .btn:hover { background-color: #0056b3; }
        .error-message { color: #721c24; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <?php if (isset($errors['general'])): ?>
            <div class="message error">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="validate-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <span class="error-message"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <?php if (isset($errors['password'])): ?>
                    <span class="error-message"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <?php if (isset($errors['password'])): ?>
                    <span class="error-message"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
