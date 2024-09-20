<?php
require_once '../config/db.php';
session_start();

class DepartmentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllDepartments() {
        $stmt = $this->pdo->query('SELECT * FROM departments');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartmentById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM departments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addDepartment($data) {
        // Check if the department name already exists
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM departments WHERE department_name = ?');
        $checkStmt->execute([$data['department_name']]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "The department name '{$data['department_name']}' already exists. Please use a different name.";
            header('Location: /UniExam/views/admin/manage_departments.php');
            exit();
        }

        // Proceed to add the department if no duplicate is found
        try {
            $stmt = $this->pdo->prepare('INSERT INTO departments (department_name, head_of_department, location) VALUES (?, ?, ?)');
            $stmt->execute([
                $data['department_name'],
                $data['head_of_department'],
                $data['location']
            ]);
            $_SESSION['success_message'] = 'Department added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add department. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_departments.php');
        exit();
    }

    public function updateDepartment($data) {
        try {
            $stmt = $this->pdo->prepare('UPDATE departments SET department_name = ?, head_of_department = ?, location = ? WHERE id = ?');
            $stmt->execute([
                $data['department_name'],
                $data['head_of_department'],
                $data['location'],
                $data['id']
            ]);
            $_SESSION['success_message'] = 'Department updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update department. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_departments.php');
        exit();
    }

    public function deleteDepartment($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM departments WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Department deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete department. Please try again.';
        }

        header('Location: /UniExam/views/admin/manage_departments.php');
        exit();
    }
}

if (isset($_GET['action'])) {
    $departmentController = new DepartmentController($pdo);
    switch ($_GET['action']) {
        case 'getAllDepartments':
            echo json_encode($departmentController->getAllDepartments());
            break;
        case 'addDepartment':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $departmentController->addDepartment($_POST);
            }
            break;
        case 'updateDepartment':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $departmentController->updateDepartment($_POST);
            }
            break;
        case 'deleteDepartment':
            if (isset($_GET['id'])) {
                $departmentController->deleteDepartment($_GET['id']);
            }
            break;
    }
}
?>
