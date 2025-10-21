<?php
// User model class

class User {
    public $user_id;
    public $full_name;
    public $username;
    public $admission_number;
    public $department;
    public $phone;
    public $email;
    public $password;
    public $role;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, username, admission_number, department, phone, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $data['full_name'], $data['username'], $data['admission_number'], $data['department'], $data['phone'], $data['email'], $data['password'], $data['role']);
        return $stmt->execute();
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        if (!$stmt) {
            die('Database error in findByUsername: ' . $this->conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findById($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ...other methods as needed (update, delete, etc.)...
}
