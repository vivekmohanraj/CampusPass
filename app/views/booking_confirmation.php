<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - CampusPass</title>
    <link rel="stylesheet" href="../../assets/css/theme.css">
    <style>
        body { background: var(--bg-secondary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .container { max-width: 600px; width: 100%; }
        .page-header { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); border-radius: var(--radius-xl); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-lg); color: white; text-align: center; }
        .page-header h2 { margin: 0; color: white; font-size: 2rem; font-weight: 700; }
        .confirm-box { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 2.5rem; text-align: center; }
        .success-icon { width: 80px; height: 80px; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; }
        .fail-icon { width: 80px; height: 80px; background: linear-gradient(135deg, var(--error) 0%, #dc2626 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; }
        .success { color: var(--success); font-weight: 700; font-size: 1.5rem; margin-bottom: 1.5rem; }
        .fail { color: var(--error); font-weight: 700; font-size: 1.5rem; margin-bottom: 1.5rem; }
        .detail-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border); }
        .detail-label { font-weight: 600; color: var(--text-secondary); }
        .detail-value { color: var(--text); font-weight: 500; }
        .divider { height: 2px; background: var(--border); margin: 1.5rem 0; }
        .back-link { display: inline-block; margin-top: 2rem; padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); color: white; border-radius: var(--radius-md); text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
        .back-link:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); text-decoration: none; }
        @media (max-width: 768px) { body { padding: 1rem; } .confirm-box { padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <?php
        require_once __DIR__ . '/../models/Event.php';
        require_once __DIR__ . '/../models/Ticket.php';
        require_once __DIR__ . '/../models/User.php';
        session_start();
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $event_id = $_GET['event_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$event_id || !$user_id) {
            echo '<div class="confirm-box"><div class="fail-icon"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></div><div class="fail">Invalid Booking</div><p>Unable to process your booking request.</p></div>';
            echo '<p style="text-align:center;"><a href="student_events.php" class="back-link">Back to Events</a></p>';
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
            echo '<div class="success-icon"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg></div>';
            echo '<div class="success">Booking Confirmed!</div>';
            echo '<p style="color: var(--text-secondary); margin-bottom: 2rem;">Your ticket has been successfully booked</p>';
            echo '<div style="text-align: left;">';
            echo '<div class="detail-row"><span class="detail-label">Event:</span><span class="detail-value">' . htmlspecialchars($event['name']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Date:</span><span class="detail-value">' . htmlspecialchars($event['date']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Time:</span><span class="detail-value">' . htmlspecialchars($event['time']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Venue:</span><span class="detail-value">' . htmlspecialchars($event['venue']) . '</span></div>';
            echo '<div class="divider"></div>';
            echo '<div class="detail-row"><span class="detail-label">Name:</span><span class="detail-value">' . htmlspecialchars($user['full_name']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Admission No:</span><span class="detail-value">' . htmlspecialchars($user['admission_number']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Ticket ID:</span><span class="detail-value">' . htmlspecialchars($ticket['ticket_id']) . '</span></div>';
            echo '<div class="detail-row"><span class="detail-label">Payment Status:</span><span class="detail-value">' . htmlspecialchars($ticket['payment_status']) . '</span></div>';
            echo '</div>';
            echo '<a href="student_events.php" class="back-link">Back to Events</a>';
            echo '</div>';
        } else {
            echo '<div class="confirm-box"><div class="fail-icon"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></div><div class="fail">Booking Not Found</div><p>We couldn\'t find your booking details.</p><a href="student_events.php" class="back-link">Back to Events</a></div>';
        }
        ?>
    </div>
</body>
</html>
