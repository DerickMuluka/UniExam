<?php
require_once __DIR__ . '/../config/db.php';

// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class LecturerController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllLecturers() {
        $stmt = $this->pdo->query('
            SELECT lecturers.*, departments.department_name, courses.course_name 
            FROM lecturers 
            JOIN courses ON lecturers.course_id = courses.id
            JOIN departments ON courses.department_id = departments.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLecturerById($id) {
        $stmt = $this->pdo->prepare('
            SELECT lecturers.*, departments.department_name, courses.course_name 
            FROM lecturers 
            JOIN courses ON lecturers.course_id = courses.id 
            JOIN departments ON courses.department_id = departments.id
            WHERE lecturers.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addLecturer($data) {
        // Check if the lecturer number already exists
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM lecturers WHERE lecturer_number = ?');
        $checkStmt->execute([$data['lecturer_number']]);
        $count = $checkStmt->fetchColumn();
    
        if ($count > 0) {
            $_SESSION['error_message'] = "The lecturer number '{$data['lecturer_number']}' already exists. Please use a different number.";
            header('Location: /UniExam/views/admin/manage_lecturers.php');
            exit();
        }
    
        // Proceed to add the lecturer if no duplicate is found
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO lecturers (lecturer_number, name, email, password, course_id) 
                VALUES (?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $data['lecturer_number'],
                $data['name'],
                $data['email'],
                md5($data['password']),
                $data['course_id']
            ]);
            $_SESSION['success_message'] = 'Lecturer added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "The lecturer number '{$data['lecturer_number']}' already exists. Please use a different code.";
        }
        header('Location: /UniExam/views/admin/manage_lecturers.php');
        exit();
    }
    

    public function deleteLecturer($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM lecturers WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Lecturer deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete Lecturer. Please try again.';
        }
        header('Location: /UniExam/views/admin/manage_lecturers.php');
    }

    public function updateLecturer($id, $data) {
        try {
            $stmt = $this->pdo->prepare('
                UPDATE lecturers 
                SET lecturer_number = ?, name = ?, email = ?, course_id = ? 
                WHERE id = ?
            ');
            $stmt->execute([
                $data['lecturer_number'],
                $data['name'],
                $data['email'],
                $data['course_id'],
                $id
            ]);
            $_SESSION['success_message'] = 'Lecturer updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update Lecturer. Please try again.';
        }
        header('Location: /UniExam/views/admin/manage_lecturers.php');
    }

    public function getCourses() {
        $stmt = $this->pdo->query('SELECT courses.id, courses.course_name, departments.department_name 
                                   FROM courses 
                                   JOIN departments ON courses.department_id = departments.id');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (isset($_GET['action'])) {
    $lecturerController = new LecturerController($pdo);
    switch ($_GET['action']) {
        case 'getAllLecturers':
            echo json_encode($lecturerController->getAllLecturers());
            break;
        case 'addLecturer':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $lecturerController->addLecturer($_POST);
            }
            break;
        case 'deleteLecturer':
            if (isset($_GET['id'])) {
                $lecturerController->deleteLecturer($_GET['id']);
            }
            break;
        case 'updateLecturer':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
                $lecturerController->updateLecturer($_GET['id'], $_POST);
            }
            break;
    }
}
?>
