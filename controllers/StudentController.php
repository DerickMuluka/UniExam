<?php
require_once __DIR__ . '/../config/db.php';
session_start(); // Start session to use flash messages

class StudentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllStudents() {
        // Join the students and courses tables to fetch the course_name
        $stmt = $this->pdo->query('
            SELECT students.id, students.registration_number, students.name, students.email, courses.course_name
            FROM students
            JOIN courses ON students.course_id = courses.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id) {
        $stmt = $this->pdo->prepare('
            SELECT students.id, students.registration_number, students.name, students.email, courses.course_name
            FROM students
            JOIN courses ON students.course_id = courses.id
            WHERE students.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addStudent($data) {
        // Check if the registration number already exists
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM students WHERE registration_number = ?');
        $checkStmt->execute([$data['registration_number']]);
        $count = $checkStmt->fetchColumn();
    
        if ($count > 0) {
            $_SESSION['error_message'] = "The registration number '{$data['registration_number']}' already exists. Please use a different number.";
            header('Location: /UniExam/views/admin/manage_students.php');
            exit();
        }
    
        // Proceed to add the student if no duplicate is found
        try {
            $stmt = $this->pdo->prepare('INSERT INTO students (registration_number, name, email, password, course_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $data['registration_number'],
                $data['name'],
                $data['email'],
                md5($data['password']),
                $data['course_id']
            ]);
            $_SESSION['success_message'] = 'Student added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "The registration number '{$data['registration_number']}' already exists. Please use a different code.";
        }
        header('Location: /UniExam/views/admin/manage_students.php');
        exit();
    }

    public function updateStudent($id, $data) {
        try {
            $stmt = $this->pdo->prepare('UPDATE students SET registration_number = ?, name = ?, email = ?, course_id = ? WHERE id = ?');
            $stmt->execute([
                $data['registration_number'],
                $data['name'],
                $data['email'],
                $data['course_id'],
                $id
            ]);
            $_SESSION['success_message'] = 'Student updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update student. Please try again.';
        }
        header('Location: /UniExam/views/admin/manage_students.php');
    }

    public function deleteStudent($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Student deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete student. Please try again.';
        }
        header('Location: /UniExam/views/admin/manage_students.php');
    }
}

if (isset($_GET['action'])) {
    $studentController = new StudentController($pdo);
    switch ($_GET['action']) {
        case 'getAllStudents':
            echo json_encode($studentController->getAllStudents());
            break;
        case 'getStudentById':
            if (isset($_GET['id'])) {
                echo json_encode($studentController->getStudentById($_GET['id']));
            }
            break;
        case 'addStudent':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $studentController->addStudent($_POST);
            }
            break;
        case 'editStudent':
            if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $studentController->updateStudent($_GET['id'], $_POST);
            } elseif (isset($_GET['id'])) {
                echo json_encode($studentController->getStudentById($_GET['id']));
            }
            break;
        case 'deleteStudent':
            if (isset($_GET['id'])) {
                $studentController->deleteStudent($_GET['id']);
            }
            break;
    }
}
?>
