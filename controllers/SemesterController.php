<?php
require_once '../config/db.php';

class SemesterController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSemesters() {
        $stmt = $this->pdo->query('SELECT * FROM semesters');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSemesterById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSemester($data) {
        // Check if the semester code already exists
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM semesters WHERE semester_code = ?');
        $checkStmt->execute([$data['semester_code']]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "The semester code '{$data['semester_code']}' already exists. Please use a different code.";
            header('Location: /UniExam/views/admin/manage_semesters.php');
            exit();
        }

        // Proceed to add the semester if no duplicate is found
        $stmt = $this->pdo->prepare('INSERT INTO semesters (course_code, semester_code, semester_name, year_of_study) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['course_code'],
            $data['semester_code'],
            $data['semester_name'],
            $data['year_of_study']
        ]);
        $_SESSION['success_message'] = "Semester added successfully!";
        header('Location: /UniExam/views/admin/manage_semesters.php');
        exit();
    }

    public function updateSemester($id, $data) {
        // Check if the semester code already exists (excluding the current record)
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM semesters WHERE semester_code = ? AND id != ?');
        $checkStmt->execute([$data['semester_code'], $id]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "The semester code '{$data['semester_code']}' already exists. Please use a different code.";
            header('Location: /UniExam/views/admin/manage_semesters.php');
            exit();
        }

        // Proceed to update the semester if no duplicate is found
        $stmt = $this->pdo->prepare('UPDATE semesters SET course_code = ?, semester_code = ?, semester_name = ?, year_of_study = ? WHERE id = ?');
        $stmt->execute([
            $data['course_code'],
            $data['semester_code'],
            $data['semester_name'],
            $data['year_of_study'],
            $id
        ]);
        $_SESSION['success_message'] = "Semester updated successfully!";
        header('Location: /UniExam/views/admin/manage_semesters.php');
        exit();
    }

    public function deleteSemester($id) {
        $stmt = $this->pdo->prepare('DELETE FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['success_message'] = "Semester deleted successfully!";
        header('Location: /UniExam/views/admin/manage_semesters.php');
        exit();
    }
}

if (isset($_GET['action'])) {
    session_start();
    $semesterController = new SemesterController($pdo);
    switch ($_GET['action']) {
        case 'getAllSemesters':
            echo json_encode($semesterController->getAllSemesters());
            break;
        case 'getSemesterById':
            if (isset($_GET['id'])) {
                echo json_encode($semesterController->getSemesterById($_GET['id']));
            }
            break;
        case 'addSemester':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $semesterController->addSemester($_POST);
            }
            break;
        case 'updateSemester':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
                $semesterController->updateSemester($_GET['id'], $_POST);
            }
            break;
        case 'deleteSemester':
            if (isset($_GET['id'])) {
                $semesterController->deleteSemester($_GET['id']);
            }
            break;
    }
}
?>
