<?php
require_once '../config/db.php';

class LecturerController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=uniexam', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM lecturers WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lecturer && password_verify($password, $lecturer['password'])) {
            session_start();
            $_SESSION['lecturer'] = $lecturer;
            header('Location: ../views/lecturers/lecturer_dashboard.php');
            exit;
        } else {
            return 'Invalid email or password';
        }
    }

    public function register($name, $email, $password)
    {
        $stmt = $this->pdo->prepare('INSERT INTO lecturers (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return 'Lecturer registered successfully!';
    }

    public function updateProfile($id, $name, $email, $password = null)
    {
        if ($password) {
            $stmt = $this->pdo->prepare('UPDATE lecturers SET name = :name, email = :email, password = :password WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id' => $id
            ]);
        } else {
            $stmt = $this->pdo->prepare('UPDATE lecturers SET name = :name, email = :email WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'id' => $id
            ]);
        }
        return 'Profile updated successfully!';
    }
}
