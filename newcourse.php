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

if (isset($_POST['courseName'])){
    $courseName = $_POST['courseName'];
    $managerRepository->saveCourse($courseName, false, $userId);
}


header('Location: index.php');