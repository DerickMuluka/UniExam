<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['lecturer'])) {
    header('Location: login.php');
    exit;
}

$lecturer = $_SESSION['lecturer'];
$lecturer_id = $lecturer['id'];

// Fetch students and their registered units under this lecturer
$query = "
    SELECT sur.student_id, s.registration_number, s.name as student_name, 
           cu.course_code, cu.unit_code, cu.unit_name, cu.id as course_unit_id
    FROM student_unit_registrations sur
    JOIN students s ON sur.student_id = s.id
    JOIN course_units cu ON sur.unit_id = cu.id
    WHERE sur.lecturer_id = :lecturer_id
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':lecturer_id', $lecturer_id);
$stmt->execute();
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Marks - UniExam</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .marks-table th, .marks-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 1rem;
            color: #555;
        }

        .marks-table th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
        }

        .marks-table tr:last-child td {
            border-bottom: none;
        }

        .btn-edit, .btn-add {
            padding: 8px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #28a745;
        }

        .btn-add {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .marks-table th, .marks-table td {
                padding: 10px;
                font-size: 0.9rem;
            }

            .btn-edit, .btn-add {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .marks-table, .marks-table th, .marks-table td {
                display: block;
                width: 100%;
            }

            .marks-table th {
                text-align: left;
                font-size: 0.85rem;
            }

            .marks-table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                font-size: 0.85rem;
            }

            .marks-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
                font-size: 0.85rem;
                color: #333;
            }

            .marks-table td:last-child {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php require_once '../partials/header.php'; ?>

    <div class="dashboard-container">
        <h2>Enter Marks</h2>
        <table class="marks-table">
            <thead>
                <tr>
                    <th>Registration Number</th>
                    <th>Student Name</th>
                    <th>Course Code</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                    <th>Marks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td data-label="Registration Number"><?= htmlspecialchars($registration['registration_number']); ?></td>
                        <td data-label="Student Name"><?= htmlspecialchars($registration['student_name']); ?></td>
                        <td data-label="Course Code"><?= htmlspecialchars($registration['course_code']); ?></td>
                        <td data-label="Unit Code"><?= htmlspecialchars($registration['unit_code']); ?></td>
                        <td data-label="Unit Name"><?= htmlspecialchars($registration['unit_name']); ?></td>
                        <td data-label="Marks">
                            <?php
                            $marksQuery = "
                                SELECT marks 
                                FROM marks 
                                WHERE registration_number = :registration_number 
                                  AND course_unit_id = :course_unit_id
                            ";
                            $marksStmt = $pdo->prepare($marksQuery);
                            $marksStmt->bindParam(':registration_number', $registration['registration_number']);
                            $marksStmt->bindParam(':course_unit_id', $registration['course_unit_id']);
                            $marksStmt->execute();
                            $result = $marksStmt->fetch(PDO::FETCH_ASSOC);
                            echo $result ? htmlspecialchars($result['marks']) : 'N/A';
                            ?>
                        </td>
                        <td data-label="Action">
                            <?php if ($result): ?>
                                <a href="edit_marks.php?registration_number=<?= htmlspecialchars($registration['registration_number']); ?>&course_unit_id=<?= htmlspecialchars($registration['course_unit_id']); ?>" class="btn-edit">Edit</a>
                            <?php else: ?>
                                <a href="add_marks.php?registration_number=<?= htmlspecialchars($registration['registration_number']); ?>&course_unit_id=<?= htmlspecialchars($registration['course_unit_id']); ?>" class="btn-add">Add</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../partials/footer.php'; ?>
</body>
</html>

