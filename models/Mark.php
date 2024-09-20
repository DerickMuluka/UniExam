<?php

class Mark {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM marks');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM marks WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUnitDetails($unit_code) {
        $stmt = $this->pdo->prepare('SELECT unit_name, lecturer_number FROM course_units WHERE unit_code = ?');
        $stmt->execute([$unit_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
