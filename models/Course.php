<?php
require_once __DIR__ . '/../config/db.php';

class Course {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM courses ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO courses (course_code, course_name, department) VALUES (?, ?, ?)');
        return $stmt->execute([
            $data['course_code'],
            $data['course_name'],
            $data['department']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>
