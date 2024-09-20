<?php
require_once __DIR__ . '/../config/db.php';

class Unit {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM units');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO units (unit_code, unit_name, course_id, semester_id, lecturer_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['unit_code'],
            $data['unit_name'],
            $data['course_id'],
            $data['semester_id'],
            $data['lecturer_id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM units WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>
