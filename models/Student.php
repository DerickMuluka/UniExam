<?php
require_once __DIR__ . '/../config/db.php';

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        // Join the students and courses tables to fetch the course_name
        $stmt = $this->pdo->query('
            SELECT students.id, students.registration_number, students.name, students.email, courses.course_name
            FROM students
            JOIN courses ON students.course_id = courses.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO students (registration_number, name, email, password, course_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['registration_number'],
            $data['name'],
            $data['email'],
            md5($data['password']),
            $data['course_id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>
