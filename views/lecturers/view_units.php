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
        l.lecturer_number, 
        l.name AS lecturer_name, 
        co.course_code, 
        s.semester_code, 
        cu.unit_code, 
        cu.unit_name, 
        s.year_of_study  -- Updated field
    FROM units u
    JOIN course_units cu ON u.course_unit_id = cu.id
    JOIN courses co ON u.course_id = co.id
    JOIN semesters s ON u.semester_id = s.id
    JOIN lecturers l ON u.lecturer_id = l.id
    WHERE l.id = :lecturer_id 
      AND s.year_of_study = (SELECT year_of_study FROM semesters ORDER BY id DESC LIMIT 1)
');
$stmt->execute(['lecturer_id' => $lecturer_id]);
$units = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Units - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .view-units-container {
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
    
    <div class="view-units-container">
        <h2>My Units</h2>
        <table>
            <thead>
                <tr>
                    <th>Lecturer Number</th>
                    <th>Lecturer Name</th>
                    <th>Course Code</th>
                    <th>Semester Code</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                    <th>Year of Study</th> <!-- Updated column name -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($units as $unit): ?>
                    <tr>
                        <td data-label="Lecturer Number"><?= htmlspecialchars($unit['lecturer_number']); ?></td>
                        <td data-label="Lecturer Name"><?= htmlspecialchars($unit['lecturer_name']); ?></td>
                        <td data-label="Course Code"><?= htmlspecialchars($unit['course_code']); ?></td>
                        <td data-label="Semester Code"><?= htmlspecialchars($unit['semester_code']); ?></td>
                        <td data-label="Unit Code"><?= htmlspecialchars($unit['unit_code']); ?></td>
                        <td data-label="Unit Name"><?= htmlspecialchars($unit['unit_name']); ?></td>
                        <td data-label="Year of Study"><?= htmlspecialchars($unit['year_of_study']); ?></td> <!-- Updated field -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
