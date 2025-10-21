<?php
// AuthController: Handles login, registration, logout

session_start();
require_once __DIR__ . '/../models/User.php';

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campus_event_ticketing';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$userModel = new User($conn);
$action = $_GET['action'] ?? '';

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $admission_number = trim($_POST['admission_number']);
    $department = $_POST['department'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    $errors = [];
    // Full name: not empty, no leading/trailing space, only letters and spaces
    if ($full_name === '' || preg_match('/^\s|\s$/', $_POST['full_name'])) {
        $errors[] = 'Full name cannot be empty or start/end with space.';
    }
    if (!preg_match('/^[A-Za-z]+(\s[A-Za-z]+)*$/', $full_name)) {
        $errors[] = 'Full name can only contain letters and spaces.';
    }
    // Username: not empty, only lowercase letters, numbers, special symbols, no leading/trailing space
    if ($username === '' || preg_match('/^\s|\s$/', $_POST['username'])) {
        $errors[] = 'Username cannot be empty or start/end with space.';
    }
    if (!preg_match('/^[a-z0-9._-]+$/', $username)) {
        $errors[] = 'Username can only contain lowercase letters, numbers, and . _ -';
    }
    // Admission number: only digits, 1-5 digits
    if ($admission_number === '' || !preg_match('/^\d{1,5}$/', $admission_number)) {
        $errors[] = 'Admission number must be 1 to 5 digits.';
    }
    // Department: must be B.Tech or Computer Applications
    if ($department !== 'B.Tech' && $department !== 'Computer Applications') {
        $errors[] = 'Invalid department selected.';
    }
    // Phone: exactly 10 digits
    if ($phone === '' || !preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Phone number must be exactly 10 digits.';
    }
    // Email: valid format
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }
    // Password: at least one uppercase, one lowercase, one number, one special symbol
    if ($password === '' ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/\d/', $password) ||
        !preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special symbol.';
    }
    if (!empty($errors)) {
        echo '<ul style="color:red;">';
        foreach ($errors as $err) echo '<li>' . htmlspecialchars($err) . '</li>';
        echo '</ul><a href="/projects/campus_event_ticketing/app/views/register.php">Go back</a>';
        exit;
    }
    $data = [
        'full_name' => $full_name,
        'username' => $username,
        'admission_number' => $admission_number,
        'department' => $department,
        'phone' => $phone,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'student'
    ];
    // Check for duplicate email, username, or admission number
    $existing = $userModel->findByEmail($data['email']);
    if ($existing) {
        die('Email already registered. <a href="/projects/campus_event_ticketing/app/views/register.php">Try again</a>');
    }
    $existingUsername = $userModel->findByUsername($data['username']);
    if ($existingUsername) {
        die('Username already taken. <a href="/projects/campus_event_ticketing/app/views/register.php">Try again</a>');
    }
    // Optionally check admission number uniqueness here as well
    if ($userModel->create($data)) {
        header('Location: /projects/campus_event_ticketing/app/views/login.php?registered=1');
        exit;
    } else {
        die('Registration failed. <a href="/projects/campus_event_ticketing/app/views/register.php">Try again</a>');
    }
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user = $userModel->findByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: /projects/campus_event_ticketing/app/views/admin_dashboard.php');
        } else {
            header('Location: /projects/campus_event_ticketing/app/views/student_dashboard.php');
        }
        exit;
    } else {
        die('Invalid credentials. <a href="/projects/campus_event_ticketing/app/views/login.php">Try again</a>');
    }
}
