<?php
// logout.php: Destroys session and redirects to login
session_start();
session_unset();
session_destroy();
header('Location: app/views/login.php');
exit;
