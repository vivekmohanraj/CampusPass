<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/event_list.css">
    <style>
        .confirm-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 24px; margin: 32px auto; max-width: 500px; }
        .success { color: #27ae60; font-weight: bold; }
        .fail { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Booking Confirmation</h2>
    <?php
    require_once __DIR__ . '/../models/Event.php';
    require_once __DIR__ . '/../models/Ticket.php';
    require_once __DIR__ . '/../models/User.php';
    session_start();
    $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
    $event_id = $_GET['event_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$event_id || !$user_id) {
        echo '<div class="fail confirm-box">Invalid booking.</div>';
        exit;
    }
    $eventModel = new Event($conn);
    $ticketModel = new Ticket($conn);
    $userModel = new User($conn);
    $event = $eventModel->findById($event_id);
    $ticket = $ticketModel->findByUserAndEvent($user_id, $event_id);
    $user = $userModel->findById($user_id);
    if ($event && $ticket && $user) {
        echo '<div class="confirm-box">';
        echo '<div class="success">Booking Confirmed!</div>';
        echo '<p><b>Event:</b> ' . htmlspecialchars($event['name']) . '</p>';
        echo '<p><b>Date:</b> ' . htmlspecialchars($event['date']) . ' <b>Time:</b> ' . htmlspecialchars($event['time']) . '</p>';
        echo '<p><b>Venue:</b> ' . htmlspecialchars($event['venue']) . '</p>';
        echo '<hr>';
        echo '<p><b>Name:</b> ' . htmlspecialchars($user['full_name']) . '</p>';
        echo '<p><b>Admission Number:</b> ' . htmlspecialchars($user['admission_number']) . '</p>';
        echo '<p><b>Ticket ID:</b> ' . htmlspecialchars($ticket['ticket_id']) . '</p>';
        echo '<p><b>Payment Status:</b> ' . htmlspecialchars($ticket['payment_status']) . '</p>';
        echo '</div>';
    } else {
        echo '<div class="fail confirm-box">Booking not found.</div>';
    }
    ?>
    <p style="text-align:center;"><a href="/projects/campus_event_ticketing/app/views/student_events.php">Back to Events</a></p>
</body>
</html>
