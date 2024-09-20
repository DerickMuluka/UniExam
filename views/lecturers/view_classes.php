<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['lecturer'])) {
    header('Location: login.php');
    exit;
}

$lecturer_id = $_SESSION['lecturer']['id'];

$stmt = $pdo->prepare('
    SELECT 
        c.id AS class_id, 
        s.semester_name, 
        cu.unit_name, 
        cu.unit_code, 
        co.course_code, 
        d.department_name,  -- Fetch the department name from the departments table
        l.lecturer_number,
        l.name AS lecturer_name
    FROM classes c
    JOIN units u ON u.class_id = c.id
    JOIN course_units cu ON u.course_unit_id = cu.id
    JOIN courses co ON c.course_id = co.id
    JOIN departments d ON co.department_id = d.id  -- Join the departments table
    JOIN semesters s ON c.semester_id = s.id
    JOIN lecturers l ON u.lecturer_id = l.id
    WHERE l.id = :lecturer_id
');
$stmt->execute(['lecturer_id' => $lecturer_id]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Classes - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .view-classes-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            overflow-x: auto;
        }
        h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        td {
            word-wrap: break-word;
        }
        @media (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            th, td {
                box-sizing: border-box;
                padding: 10px;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 10px;
                display: block;
                border-bottom: 1px solid #ddd;
            }
            td {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #ddd;
                position: relative;
                padding-left: 50%;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>
    
    <div class="view-classes-container">
        <h2>My Classes</h2>
        <table>
            <thead>
                <tr>
                    <th>Lecturer Number</th>
                    <th>Lecturer Name</th>
                    <th>Course Code</th>
                    <th>Department</th>
                    <th>Semester Name</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= htmlspecialchars($class['lecturer_number']); ?></td>
                        <td><?= htmlspecialchars($class['lecturer_name']); ?></td>
                        <td><?= htmlspecialchars($class['course_code']); ?></td>
                        <td><?= htmlspecialchars($class['department_name']); ?></td>
                        <td><?= htmlspecialchars($class['semester_name']); ?></td>
                        <td><?= htmlspecialchars($class['unit_code']); ?></td>
                        <td><?= htmlspecialchars($class['unit_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
