<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['student'])) {
    echo json_encode(['status' => false, 'message' => 'You must be logged in.']);
    exit;
}

$student = $_SESSION['student'];
$student_id = $student['id'];
$unit_id = $_POST['unit_id'];
$action = $_POST['action'];
$semester_id = $_GET['semester_id'];

if ($action == 'register') {
    // Register the unit
    $query = "
        INSERT INTO student_unit_registrations (student_id, unit_id, semester_id) 
        VALUES (:student_id, :unit_id, :semester_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
    $stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'Unit registered successfully.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to register unit.']);
    }
} elseif ($action == 'unregister') {
    // Unregister the unit
    $query = "
        DELETE FROM student_unit_registrations 
        WHERE student_id = :student_id 
        AND unit_id = :unit_id 
        AND semester_id = :semester_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
    $stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'Unit unregistered successfully.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to unregister unit.']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid action.']);
}
