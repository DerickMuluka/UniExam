<?php
require_once __DIR__ . '/../config/db.php';

class Result {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM results');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM results WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($student_id, $semester_id, $total_marks, $position) {
        $stmt = $this->pdo->prepare('INSERT INTO results (student_id, semester_id, total_marks, position) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$student_id, $semester_id, $total_marks, $position]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM results WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

