<?php
// Base URL Configuration
// Change this to match your deployment path
// Local: '/projects/campus_event_ticketing'
// EC2: '/CampusPass'
define('BASE_PATH', '/CampusPass');

// Or use auto-detection (recommended)
// This will automatically detect the base path
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePathAuto = str_replace('/app/views', '', dirname($scriptName));
define('BASE_URL', $basePathAuto);
?>
