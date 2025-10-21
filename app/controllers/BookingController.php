<?php
// BookingController: Handles ticket booking and confirmation
session_start();
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Event.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campus_event_ticketing';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$ticketModel = new Ticket($conn);
$eventModel = new Event($conn);
$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if ($action === 'book' && $_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $event_id = intval($_POST['event_id']);
    $payment_id = $_POST['payment_id'] ?? null;
    // Check if already booked
    $already = $ticketModel->findByUserAndEvent($user_id, $event_id);
    if ($already) {
        die('You have already booked a ticket for this event. <a href="/projects/campus_event_ticketing/app/views/student_events.php">Back to Events</a>');
    }
    // Check event status and tickets remaining
    $event = $eventModel->findById($event_id);
    if (!$event || $event['status'] === 'Completed') {
        die('Event is completed or not found. <a href="/projects/campus_event_ticketing/app/views/student_events.php">Back to Events</a>');
    }
    $max = $event['max_attendees'];
    $price = $event['price'];
    $tickets_q = $conn->query("SELECT COUNT(*) as cnt FROM tickets WHERE event_id=$event_id");
    $tickets_count = $tickets_q->fetch_assoc()['cnt'];
    if ($max && $max > 0 && $tickets_count >= $max) {
        die('Tickets sold out. <a href="/projects/campus_event_ticketing/app/views/student_events.php">Back to Events</a>');
    }
    if ($price == 0) {
        $payment_status = 'Free';
        $ticketModel->create(['event_id' => $event_id, 'user_id' => $user_id, 'payment_status' => $payment_status]);
        header('Location: /projects/campus_event_ticketing/app/views/booking_confirmation.php?event_id=' . $event_id);
        exit;
    } else {
        // Paid event: payment_id must be present
        if (!$payment_id) {
            die('Payment not completed. <a href="/projects/campus_event_ticketing/app/views/student_events.php">Try again</a>');
        }
        // Save payment record
        require_once __DIR__ . '/../models/Payment.php';
        $paymentModel = new Payment($conn);
        $paymentModel->create([
            'payment_id' => $payment_id,
            'user_id' => $user_id,
            'event_id' => $event_id,
            'amount' => $price,
            'status' => 'Success'
        ]);
        $ticketModel->create(['event_id' => $event_id, 'user_id' => $user_id, 'payment_status' => 'Success']);
        header('Location: /projects/campus_event_ticketing/app/views/booking_confirmation.php?event_id=' . $event_id);
        exit;
    }
}
// ...other actions (payment integration) can be added here...
