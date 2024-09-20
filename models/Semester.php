<?php
require_once __DIR__ . '/../config/db.php';

class Semester {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSorted() {
        $stmt = $this->pdo->query('
            SELECT * FROM semesters
            ORDER BY course_code ASC, 
                     CAST(semester_code AS UNSIGNED) ASC, 
                     year_of_study ASC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO semesters (course_code, semester_code, semester_name, year_of_study, academic_year) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['course_code'],
            $data['semester_code'],
            $data['semester_name'],
            $data['year_of_study'],
            $data['academic_year']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE semesters SET course_code = ?, semester_code = ?, semester_name = ?, year_of_study = ?, academic_year = ? WHERE id = ?');
        return $stmt->execute([
            $data['course_code'],
            $data['semester_code'],
            $data['semester_name'],
            $data['year_of_study'],
            $data['academic_year'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM semesters WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>
