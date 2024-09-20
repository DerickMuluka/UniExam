document.addEventListener('DOMContentLoaded', function () {
    // Load Students via AJAX
    const loadStudentsBtn = document.getElementById('load-students');
    if (loadStudentsBtn) {
        loadStudentsBtn.addEventListener('click', function () {
            fetch('/controllers/StudentController.php?action=getAllStudents')
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.querySelector('table tbody');
                    tableBody.innerHTML = '';
                    data.forEach(student => {
                        let row = `<tr>
                            <td>${student.registration_number}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>${student.course_id}</td>
                            <td>${student.year_of_study}</td>
                            <td>
                                <a href="edit_student.php?id=${student.id}" class="btn btn-edit">Edit</a>
                                <a href="delete_student.php?id=${student.id}" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                            </td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
