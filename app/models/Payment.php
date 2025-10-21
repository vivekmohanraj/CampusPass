<?php
// Payment model class

class Payment {
    public $payment_id;
    public $user_id;
    public $event_id;
    public $amount;
    public $status;
    public $created_at;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO payments (payment_id, user_id, event_id, amount, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siids", $data['payment_id'], $data['user_id'], $data['event_id'], $data['amount'], $data['status']);
        return $stmt->execute();
    }

    public function findByPaymentId($payment_id) {
        $stmt = $this->conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
        $stmt->bind_param("s", $payment_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ...other methods as needed (update, delete, etc.)...
}
