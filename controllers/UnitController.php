<?php
require_once __DIR__ . '/../config/db.php';
session_start();

class UnitController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUnits() {
        $stmt = $this->pdo->query("SELECT id, course_code, unit_code, unit_name, semester_code FROM course_units ORDER BY semester_code ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnitById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM course_units WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCourseCodes() {
        $stmt = $this->pdo->query('SELECT DISTINCT course_code FROM courses ORDER BY course_code ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSemesterCodes() {
        $stmt = $this->pdo->query('SELECT DISTINCT semester_code FROM semesters ORDER BY semester_code ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUnit($data) {
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM course_units WHERE unit_code = ?');
        $checkStmt->execute([$data['unit_code']]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "The unit code '{$data['unit_code']}' already exists. Please use a different code.";
            header('Location: /UniExam/views/admin/manage_units.php');
            exit();
        }

        try {
            $stmt = $this->pdo->prepare('INSERT INTO course_units (course_code, unit_code, unit_name, semester_code) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $data['course_code'],
                $data['unit_code'],
                $data['unit_name'],
                $data['semester_code']
            ]);
            $_SESSION['success_message'] = 'Unit added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add unit. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_units.php');
        exit();
    }

    public function editUnit($data) {
        try {
            $stmt = $this->pdo->prepare('UPDATE course_units SET course_code = ?, unit_code = ?, unit_name = ?, semester_code = ? WHERE id = ?');
            $stmt->execute([
                $data['course_code'],
                $data['unit_code'],
                $data['unit_name'],
                $data['semester_code'],
                $data['id']
            ]);
            $_SESSION['success_message'] = 'Unit updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update unit. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_units.php');
        exit();
    }

    public function deleteUnit($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM course_units WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Unit deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete unit. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_units.php');
        exit();
    }
}

if (isset($_GET['action'])) {
    $unitController = new UnitController($pdo);
    switch ($_GET['action']) {
        case 'getAllUnits':
            echo json_encode($unitController->getAllUnits());
            break;
        case 'getUnitById':
            echo json_encode($unitController->getUnitById($_GET['id']));
            break;
        case 'addUnit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $unitController->addUnit($_POST);
            }
            break;
        case 'editUnit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $unitController->editUnit(array_merge($_POST, ['id' => $_GET['id']]));
            }
            break;
        case 'deleteUnit':
            if (isset($_GET['id'])) {
                $unitController->deleteUnit($_GET['id']);
            }
            break;
        default:
            $_SESSION['error_message'] = 'Invalid action.';
            header('Location: /UniExam/views/admin/manage_units.php');
            exit();
    }
}
?>
