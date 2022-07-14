<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	unset($_SESSION['user_id']);
	header('Location: login.php');
	exit(0);
}
