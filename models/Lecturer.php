<?php
require_once __DIR__ . '/../config/db.php';

class Lecturer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM lecturers');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO lecturers (lecturer_number, name, email, password, department) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['lecturer_number'],
            $data['name'],
            $data['email'],
            md5($data['password']),
            $data['department']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM lecturers WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>
