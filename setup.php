<?php
// setup.php: Auto-configuration script for Campus Event Ticketing System
// This script will create the database, tables, and initial admin account on first run.

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campus_event_ticketing';
$lock_file = __DIR__ . '/.setup_lock';

if (file_exists($lock_file)) {
    die('Setup has already been completed. Remove .setup_lock to rerun.');
}

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);

// Create users table
$conn->query("CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    admission_number VARCHAR(5) UNIQUE NOT NULL,
    department ENUM('B.Tech', 'Computer Applications') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student' NOT NULL
)");

// Create events table
$conn->query("CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100),
    date DATE,
    time TIME,
    venue VARCHAR(255),
    price DECIMAL(10,2) DEFAULT 0,
    max_attendees INT,
    status ENUM('Upcoming', 'Completed') DEFAULT 'Upcoming'
)");

// Create tickets table
$conn->query("CREATE TABLE IF NOT EXISTS tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_status ENUM('Success', 'Free', 'Failed'),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)");

// Create payments table
$conn->query("CREATE TABLE IF NOT EXISTS payments (
    payment_id VARCHAR(100) PRIMARY KEY,
    user_id INT,
    event_id INT,
    amount DECIMAL(10,2),
    status ENUM('Success', 'Failed'),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)");

// Insert initial admin if not exists
$admin_email = 'admin@campus.com';
$admin_username = 'admin';
$admin_admission = '00001';
$admin_department = 'B.Tech';
$admin_phone = '9999999999';
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$check_admin = $conn->query("SELECT * FROM users WHERE email='$admin_email' AND role='admin'");
if ($check_admin->num_rows == 0) {
    $conn->query("INSERT INTO users (full_name, username, admission_number, department, phone, email, password, role) VALUES ('Admin', '$admin_username', '$admin_admission', '$admin_department', '$admin_phone', '$admin_email', '$admin_pass', 'admin')");
}

// Create lock file to prevent rerun
file_put_contents($lock_file, 'Setup completed on ' . date('Y-m-d H:i:s'));

// Success message
?>
<!DOCTYPE html>
<html><head><title>Setup Complete</title></head><body>
<h2>Setup Complete!</h2>
<p>Database and tables created. Initial admin: <b>admin@campus.com</b> / <b>admin123</b></p>
<p>For security, you should delete or rename setup.php now.</p>
</body></html>
