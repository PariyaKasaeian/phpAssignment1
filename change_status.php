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

$courseName = $_POST['courseName'];

$managerRepository->updateStatus(isset($_POST["status"]), $courseName);

header('Location: index.php');