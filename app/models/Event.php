<?php
// Event model class

class Event {
    public $id;
    public $name;
    public $type;
    public $date;
    public $time;
    public $venue;
    public $price;
    public $max_attendees;
    public $status;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO events (name, type, date, time, venue, price, max_attendees, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $data['name'], $data['type'], $data['date'], $data['time'], $data['venue'], $data['price'], $data['max_attendees'], $data['status']);
        return $stmt->execute();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllUpcoming() {
        $today = date('Y-m-d');
        $stmt = $this->conn->prepare("SELECT * FROM events WHERE date >= ? ORDER BY date, time");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE events SET name=?, type=?, date=?, time=?, venue=?, price=?, max_attendees=?, status=? WHERE id=?");
        $stmt->bind_param("sssssdssi", $data['name'], $data['type'], $data['date'], $data['time'], $data['venue'], $data['price'], $data['max_attendees'], $data['status'], $id);
        return $stmt->execute();
    }

    // ...other methods as needed (delete, etc.)...
}
