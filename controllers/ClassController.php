<?php
require_once __DIR__ . '/../config/db.php';

class ClassController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add a new class
    public function addClass($data) {
        try {
            // Fetch IDs based on form input data
            $lecturer_id = $this->pdo->query("SELECT id FROM lecturers WHERE lecturer_number = '{$data['lecturer_id']}'")->fetchColumn();
            $semester_id = $this->pdo->query("SELECT id FROM semesters WHERE semester_code = '{$data['semester_id']}'")->fetchColumn();
            $course_id = $this->pdo->query("SELECT id FROM courses WHERE course_code = '{$data['course_id']}'")->fetchColumn();
            $unit_id = $this->pdo->query("SELECT id FROM course_units WHERE unit_code = '{$data['unit_id']}'")->fetchColumn();

            // Insert into the classes table
            $stmt = $this->pdo->prepare("INSERT INTO classes (course_id, semester_id, lecturer_id) VALUES (?, ?, ?)");
            $stmt->execute([$course_id, $semester_id, $lecturer_id]);

            // Get the last inserted class id
            $class_id = $this->pdo->lastInsertId();

            // Insert into the units table
            $stmt = $this->pdo->prepare("INSERT INTO units (course_unit_id, course_id, semester_id, lecturer_id, class_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$unit_id, $course_id, $semester_id, $lecturer_id, $class_id]);

            $_SESSION['success_message'] = 'Class added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to add class. Please try again. Error: ' . $e->getMessage();
        }

        header('Location: /UniExam/views/admin/manage_classes.php');
        exit();
    }

    // Get all classes (used for displaying classes in admin view)
    public function getAllClasses() {
        $stmt = $this->pdo->query("
            SELECT c.id, l.lecturer_number, l.name AS lecturer_name, 
                   co.course_code, co.course_name, 
                   s.semester_code, s.semester_name,
                   cu.unit_code, cu.unit_name
            FROM classes c
            JOIN lecturers l ON c.lecturer_id = l.id
            JOIN semesters s ON c.semester_id = s.id
            JOIN courses co ON co.id = c.course_id
            JOIN units u ON u.class_id = c.id
            JOIN course_units cu ON cu.id = u.course_unit_id
            ORDER BY co.course_code ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get class details by ID
    public function getClassById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.id, l.lecturer_number, l.name AS lecturer_name, 
                   co.course_code, co.course_name, 
                   s.semester_code, s.semester_name,
                   cu.unit_code, cu.unit_name
            FROM classes c
            JOIN lecturers l ON c.lecturer_id = l.id
            JOIN semesters s ON c.semester_id = s.id
            JOIN courses co ON co.id = c.course_id
            JOIN units u ON u.class_id = c.id
            JOIN course_units cu ON cu.id = u.course_unit_id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Edit a class
    public function editClass($data, $class_id) {
        try {
            // Fetch IDs based on form input data
            $lecturer_id = $this->pdo->query("SELECT id FROM lecturers WHERE lecturer_number = '{$data['lecturer_id']}'")->fetchColumn();
            $semester_id = $this->pdo->query("SELECT id FROM semesters WHERE id = '{$data['semester_id']}'")->fetchColumn();
            $course_id = $this->pdo->query("SELECT id FROM courses WHERE course_code = '{$data['course_id']}'")->fetchColumn();
            $unit_id = $this->pdo->query("SELECT id FROM course_units WHERE id = '{$data['unit_id']}'")->fetchColumn();

            // Update the classes table
            $stmt = $this->pdo->prepare("UPDATE classes SET course_id = ?, semester_id = ?, lecturer_id = ? WHERE id = ?");
            $stmt->execute([$course_id, $semester_id, $lecturer_id, $class_id]);

            // Update the units table
            $stmt = $this->pdo->prepare("UPDATE units SET course_unit_id = ?, course_id = ?, semester_id = ?, lecturer_id = ? WHERE class_id = ?");
            $stmt->execute([$unit_id, $course_id, $semester_id, $lecturer_id, $class_id]);

            $_SESSION['success_message'] = 'Class updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update class. Please try again. Error: ' . $e->getMessage();
        }

        header('Location: /UniExam/views/admin/manage_classes.php');
        exit();
    }

    // Delete class
    public function deleteClass($class_id) {
        try {
            // Delete the class from the classes table
            $stmt = $this->pdo->prepare("DELETE FROM classes WHERE id = ?");
            $stmt->execute([$class_id]);

            // Delete associated units
            $stmt = $this->pdo->prepare("DELETE FROM units WHERE class_id = ?");
            $stmt->execute([$class_id]);

            $_SESSION['success_message'] = 'Class deleted successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to delete class. Please try again. Error: ' . $e->getMessage();
        }

        header('Location: /UniExam/views/admin/manage_classes.php');
        exit();
    }
}

// Instantiate ClassController and handle actions
$action = $_GET['action'] ?? null;
$classController = new ClassController($pdo);

switch ($action) {
    case 'addClass':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $classController->addClass($_POST);
        }
        break;

    case 'editClass':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class_id = $_GET['id'] ?? null;
            if ($class_id) {
                $classController->editClass($_POST, $class_id);
            }
        }
        break;

    case 'deleteClass':
        $class_id = $_GET['id'] ?? null;
        if ($class_id) {
            $classController->deleteClass($class_id);
        }
        break;

    default:
        // Handle default or error cases
        break;
}
