<?php

require_once 'Repositories/UserRepository.php';
require_once 'Models/User.php';

use src\Repositories\UserRepository;

ob_start();

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit(0);
}

$authenticatedUser = (new UserRepository())->getUserById($_SESSION['user_id']);
?>

<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">

                <div class="flex-shrink-0 flex items-center text-lg text-white">
                    Course Manager.
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-4">
                    <a href="dashboard.php" class=" text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                </div>

            </div>
            <div class="flex items-center">
                <div class="flex-shrink-0 text-white">
                    <span>Welcome, <?= $authenticatedUser->name ?>!&nbsp;&nbsp;</span>
                </div>
                <div>
                    <form id="logout-form" action="logout.php" method="POST">
                        <svg onclick="logout()" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 clickable"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    logout = () => {
        document.getElementById('logout-form').submit();
    }
</script>

<style>
    .clickable {
        cursor: pointer;
    }
</style>