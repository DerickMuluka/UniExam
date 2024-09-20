<?php
require_once __DIR__ . '/../config/db.php';

// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MarkController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMarks() {
        $stmt = $this->pdo->query("
            SELECT m.*, cu.unit_code, cu.unit_name 
            FROM marks m
            JOIN course_units cu ON m.course_unit_id = cu.id
            ORDER BY m.semester_code ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMarkById($id) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, cu.unit_code, cu.unit_name 
            FROM marks m
            JOIN course_units cu ON m.course_unit_id = cu.id
            WHERE m.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMark($data) {
        // Check if the student already has marks filed for the same unit code
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM marks WHERE registration_number = ? AND course_unit_id = ?');
        $checkStmt->execute([$data['registration_number'], $data['unit_code']]);
        $count = $checkStmt->fetchColumn();
    
        if ($count > 0) {
            $_SESSION['error_message'] = "Marks have already been filed for student '{$data['registration_number']}' for the unit code '{$data['unit_code']}'. Please check the details.";
            header('Location: /UniExam/views/admin/manage_marks.php');
            exit();
        }
    
        // Proceed to add the mark if no existing marks are found
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO marks (registration_number, course_unit_id, course_code, semester_code, lecturer_number, marks) 
                VALUES (:registration_number, :course_unit_id, :course_code, :semester_code, :lecturer_number, :marks)
            ");
            $stmt->execute([
                ':registration_number' => $data['registration_number'],
                ':course_unit_id' => $data['unit_code'],
                ':course_code' => $data['course_code'],
                ':semester_code' => $data['semester_code'],
                ':lecturer_number' => $data['lecturer_number'],
                ':marks' => $data['marks'],
            ]);
            $_SESSION['success_message'] = 'Mark added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add mark. Please try again.';
        }
    
        header('Location: /UniExam/views/admin/manage_marks.php');
        exit();
    }
        
    public function updateMark($data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE marks
                SET registration_number = :registration_number,
                    course_unit_id = :course_unit_id,
                    course_code = :course_code,
                    semester_code = :semester_code,
                    lecturer_number = :lecturer_number,
                    marks = :marks
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $data['id'],  // Ensure the correct ID is being passed
                ':registration_number' => $data['registration_number'],
                ':course_unit_id' => $data['unit_code'],
                ':course_code' => $data['course_code'],
                ':semester_code' => $data['semester_code'],
                ':lecturer_number' => $data['lecturer_number'],
                ':marks' => $data['marks'],
            ]);
            $_SESSION['success_message'] = 'Mark updated successfully.';
        } catch (PDOException $e) {
            var_dump($stmt->errorInfo()); // Debugging output
            $_SESSION['error_message'] = 'Failed to update mark. ' . $e->getMessage();
        }
    
        header('Location: /UniExam/views/admin/manage_marks.php');
        exit();
    }
    
    

    public function deleteMark($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM marks WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $_SESSION['success_message'] = 'Mark deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete mark. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_marks.php');
        exit();
    }

    public function getUnitDetails($unit_code) {
        $stmt = $this->pdo->prepare("
            SELECT lecturer_number 
            FROM course_units 
            WHERE unit_code = :unit_code
        ");
        $stmt->execute(['unit_code' => $unit_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getUnitDetails' && isset($_GET['unit_code'])) {
    $controller = new MarkController($pdo);
    $unitDetails = $controller->getUnitDetails($_GET['unit_code']);
    echo json_encode($unitDetails);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'deleteMark' && isset($_GET['id'])) {
    $controller = new MarkController($pdo);
    $controller->deleteMark($_GET['id']);
}

if (isset($_GET['action'])) {
    $markController = new MarkController($pdo);
    switch ($_GET['action']) {
        case 'getAllMarks':
            echo json_encode($markController->getAllMarks());
            break;
        case 'addMark':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $markController->addMark($_POST);
            }
            break;
        case 'updateMark':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $markController->updateMark($_POST);
            }
            break;
        case 'deleteMark':
            if (isset($_GET['id'])) {
                $markController->deleteMark($_GET['id']);
            }
            break;
    }
}