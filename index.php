<?php
// Redirections will cause an authenticated user hitting the index page to go to the login.php page and then posts.php,
// and an unauthenticated user will stay at login.php
header("Location: login.php");
