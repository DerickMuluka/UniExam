<?php require_once '../partials/header.php'; ?>
<div class="container">
    <h2>View Results</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Semester ID</th>
                <th>Total Marks</th>
                <th>Position</th>
            </tr>
        </thead>
        <tbody>
            <?php
             require_once __DIR__ . '/../../config/db.php';
             require_once __DIR__ . '/../../models/Result.php';
            $resultModel = new Result($pdo);
            $results = $resultModel->getAll();

            foreach ($results as $result) {
                echo "<tr>";
                echo "<td>{$result['student_id']}</td>";
                echo "<td>{$result['semester_id']}</td>";
                echo "<td>{$result['total_marks']}</td>";
                echo "<td>{$result['position']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php require_once '../partials/footer.php'; ?>
