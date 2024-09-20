<?php
session_start();
require_once '../../config/db.php';

$registration_number = $_GET['registration_number'] ?? '';

if ($registration_number) {
    $stmt = $pdo->prepare('
        SELECT name AS student_name
        FROM students
        WHERE registration_number = :registration_number
    ');
    $stmt->execute(['registration_number' => $registration_number]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($student);
}
?>
