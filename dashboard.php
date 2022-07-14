<?php require_once 'header.php'; ?>

<?php

require_once './Repositories/ManagerRepository.php';

use src\Repositories\ManagerRepository;

session_start();
$courses = [];
if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
    $managerRepository = new ManagerRepository();
	$courses = $managerRepository->getManagerForUser($userId);
} else {
	header('Location: login.php');
}
?>
<body>
    
    <?php require_once 'nav.php' ?>

    <div>
        <form action="newcourse.php" method="post">
        <input type="text" name="courseName" placeholder="Enter your course">
        <button>New Course</button>
        </form>
        <br>
        <!-- Loop through each of the courses -->
        <?php foreach ($courses as $index => $course): ?>
        <div style="margin-bottom: 20px;">

        <!-- Checkbox Form -->
        <form style="display: inline" action="change_status.php" method="post">
            <input type="hidden" name="courseName" value="<?= $course->courseName ?>">
            <input type="checkbox" name="status" value="1" <?= $course->checked? 'checked' : '' ?>>
        </form>
        <!-- Checkbox Form End  -->

        <!-- Editable Course Title  -->
        <span class="courseTitle" data-originalcoursename="<?= $course->courseName ?>" contentEditable="true">
            <?php echo $course->courseName  ?></span>
        <!-- Editable Course Title End  -->

        <!-- Delete Button Form  -->
        <form style="display: inline" action="delete.php" method="post">
            <input type="hidden" name="courseName" value="<?= $course->courseName ?>">
            <button>Delete</button>
        </form>
        <!-- Delete Button Form End  -->

        </div>
        <?php endforeach; ?>

        <!-- Update Button Form  -->
        <form style="display: none;" id="updateForm" action="updateCourse.php" method="post">
        <input type="hidden" name="courseName" value="<?= $course->courseName ?>">
        <button id="updateButton">Update</button>
        </form>
        <!-- Update Button Form End  -->
    </div>

    <script>
    /* CODE TO HANDLE CHECKBOX FUNCTIONALITY */
    const checkboxes = document.querySelectorAll('input[type=checkbox]');
    checkboxes.forEach(ch => {
        ch.onclick = function() {
        this.parentNode.submit()
        };
    })

    /* CODE TO HANDLE EDITING TITLES */
    const editedCourses = [];
    const editableCourseTitles = document.querySelectorAll('.courseTitle');
    const updateButton = document.querySelector('#updateButton');

    // Event Handler for when you click out of content-editable
    editableCourseTitles.forEach(course => course.addEventListener("blur", (e) => {
        const updateForm = document.querySelector('#updateForm');
        updateForm.style.display = "block";
        editedCourses.push({
        "originalCourseTitle": e.target.getAttribute("data-originalcoursename"),
        "newCourseTitle": e.target.innerText
        });
    }));

    // Event Handler for when you click on the update button
    updateButton.addEventListener("click", async () => {
        const response = await fetch('/updateCourse.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(editedCourses)
        });
    });
    </script>
</body>