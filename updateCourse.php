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

$rawPostBody = file_get_contents("php://input");
/*
Structure will look like this:
(
    [0] => stdClass Object
        (
            [originalCourseTitle] => COMP3015 PHP
            [newCourseTitle] => wefew
        )

    [1] => stdClass Object
        (
            [originalCourseTitle] => COMP3012 Node
            [newCourseTitle] => yyyy
        )

)
*/

$json = (array) json_decode($rawPostBody);

/*
Payload looks like this:
(
    [originalCourseTitle] => COMP3015 PHP
    [newCourseTitle] => COMP3040
)
*/
foreach ($json as $index => $payload) {
  $payloadAsArray = json_decode(json_encode($payload), true);
  $managerRepository->updateCourse($payloadAsArray["originalCourseName"], $payloadAsArray["newCourseName"]);
}


header('Location: index.php');