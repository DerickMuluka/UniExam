<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniExam | Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
    
        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        p {
            text-align: center;
            margin: 15px 0;
            font-size: 1.1em;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .btn:active {
            background-color: #004494;
        }

        /* Flex layout for the admin links */
        .admin-links {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .admin-item {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .admin-item:hover {
            transform: translateY(-5px);
        }

        .admin-item p {
            margin: 0;
        }

        

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.9em;
            }

            h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <?php include('views/partials/header.php'); ?>

    <div class="container">
        <h1 style="border-bottom: 2px solid black;">Welcome to UniExam Admin Panel</h1>
        

        <div class="admin-links">
            <div class="admin-item">
                <p><a href="views/admin/manage_students.php" class="btn">Manage Students</a></p>
                <p>View and manage student profiles, registrations, and academic information.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_courses.php" class="btn">Manage Courses</a></p>
                <p>Manage course details including course codes, descriptions, and credits.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_lecturers.php" class="btn">Manage Lecturers</a></p>
                <p>Handle lecturer profiles, course assignments, and academic roles.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_departments.php" class="btn">Manage Departments</a></p>
                <p>Oversee department data, heads of department, and related administrative information.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_semesters.php" class="btn">Manage Semesters</a></p>
                <p>Set up and manage semester periods, start and end dates, and enrollment timelines.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_units.php" class="btn">Manage Units</a></p>
                <p>Manage academic units offered under various courses in each semester.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_marks.php" class="btn">Manage Marks</a></p>
                <p>Input, view, and manage students' exam marks and academic performance records.</p>
            </div>
            <div class="admin-item">
                <p><a href="views/admin/manage_classes.php" class="btn">Manage Classes</a></p>
                <p>Assign students and lecturers to class groups and manage timetables.</p>
            </div>
        </div>
    </div>

    <?php include('views/partials/footer.php'); ?>
</body>
</html>
