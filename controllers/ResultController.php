<?php

require_once '../config/db.php';
require_once '../models/Result.php';

$action = $_GET['action'] ?? '';

$resultModel = new Result($pdo);

switch ($action) {
    case 'addResult':
        $student_id = $_POST['student_id'];
        $semester_id = $_POST['semester_id'];
        $total_marks = $_POST['total_marks'];
        $position = $_POST['position'];

        if ($resultModel->add($student_id, $semester_id, $total_marks, $position)) {
            header('Location: /views/admin/view_results.php');
        } else {
            echo "Error adding result";
        }
        break;

    case 'deleteResult':
        $id = $_GET['id'];

        if ($resultModel->delete($id)) {
            header('Location: /views/admin/view_results.php');
        } else {
            echo "Error deleting result";
        }
        break;

    default:
        echo "Invalid action";
        break;
}
