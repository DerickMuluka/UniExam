<?php
require_once __DIR__ . '/../config/db.php';

class CourseController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCourses() {
        $stmt = $this->pdo->query('
            SELECT c.id, c.course_code, c.course_name, d.department_name, d.head_of_department, d.location 
            FROM courses c 
            JOIN departments d ON c.department_id = d.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCoursesOrderedByCode() {
        $stmt = $this->pdo->query('
            SELECT c.id, c.course_code, c.course_name, d.department_name, d.head_of_department, d.location 
            FROM courses c 
            JOIN departments d ON c.department_id = d.id
        ORDER BY c.course_code ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getCourseById($id) {
        $stmt = $this->pdo->prepare('
            SELECT c.id, c.course_code, c.course_name, c.department_id, d.department_name, d.head_of_department, d.location 
            FROM courses c 
            JOIN departments d ON c.department_id = d.id
            WHERE c.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllDepartments() {
        $stmt = $this->pdo->query('SELECT id, department_name FROM departments');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourse($data) {
        // Check if the course code already exists
        $checkStmt = $this->pdo->prepare('SELECT COUNT(*) FROM courses WHERE course_code = ?');
        $checkStmt->execute([$data['course_code']]);
        $count = $checkStmt->fetchColumn();
    
        if ($count > 0) {
            $_SESSION['error_message'] = "The course code '{$data['course_code']}' already exists. Please use a different code.";
            header('Location: /UniExam/views/admin/manage_courses.php');
            exit();
        }
    
        // Proceed to add the course if no duplicate is found
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO courses (course_code, course_name, department_id) 
                VALUES (?, ?, ?)
            ');
            $stmt->execute([
                $data['course_code'],
                $data['course_name'],
                $data['department_id']
            ]);
            $_SESSION['success_message'] = 'Course added successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "The course code '{$data['course_code']}' already exists. Please use a different code.";
        }
        header('Location: /UniExam/views/admin/manage_courses.php');
        exit();
    }
    

    public function updateCourse($id, $data) {
        try {
            $stmt = $this->pdo->prepare('
                UPDATE courses 
                SET course_code = ?, course_name = ?, department_id = ? 
                WHERE id = ?
            ');
            $stmt->execute([
                $data['course_code'],
                $data['course_name'],
                $data['department_id'],
                $id
            ]);
            $_SESSION['success_message'] = 'Course updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Failed to update course. Please try again.';
        }
        header('Location: /UniExam/views/admin/manage_courses.php');
        exit(); // Ensure no further code is executed
    }

    public function deleteCourse($id) {
        // Check if any students are associated with this course
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM students WHERE course_id = ?');
        $stmt->execute([$id]);
        $studentCount = $stmt->fetchColumn();

        if ($studentCount > 0) {
            $_SESSION['error_message'] = 'Cannot delete course. It is associated with students.';
            header('Location: /UniExam/views/admin/manage_courses.php');
            exit();
        } else {
            $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Course deleted successfully.';
            header('Location: /UniExam/views/admin/manage_courses.php');
            exit(); // Ensure no further code is executed
        }
    }
}

// Handle form actions based on 'action' parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    session_start(); // Start the session for flash messages

    $controller = new CourseController($pdo);

    if ($_GET['action'] === 'addCourse') {
        $controller->addCourse($_POST);
    } elseif ($_GET['action'] === 'updateCourse' && isset($_GET['id'])) {
        $controller->updateCourse($_GET['id'], $_POST);
    }
}

// Handle other GET actions like delete
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    session_start(); // Start the session for flash messages

    $controller = new CourseController($pdo);

    if ($_GET['action'] === 'deleteCourse' && isset($_GET['id'])) {
        $controller->deleteCourse($_GET['id']);
    }
}
