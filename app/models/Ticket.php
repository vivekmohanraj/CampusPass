<?php
// Ticket model class

class Ticket {
    public $ticket_id;
    public $event_id;
    public $user_id;
    public $booking_date;
    public $payment_status;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO tickets (event_id, user_id, payment_status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $data['event_id'], $data['user_id'], $data['payment_status']);
        return $stmt->execute();
    }

    public function findByUserAndEvent($user_id, $event_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTicketsByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ...other methods as needed (update, delete, etc.)...
}
