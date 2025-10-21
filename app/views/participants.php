<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Participants</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/admin_dashboard.css">
    <style>
        .table-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 24px; margin: 32px auto; max-width: 900px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; }
        th { background: var(--accent); color: #fff; }
    </style>
</head>
<body>
    <div class="table-box">
        <h2>Event Participants</h2>
        <?php
        require_once __DIR__ . '/../models/Ticket.php';
        require_once __DIR__ . '/../models/Event.php';
        require_once __DIR__ . '/../models/User.php';
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $ticketModel = new Ticket($conn);
        $eventModel = new Event($conn);
        $userModel = new User($conn);
        $result = $conn->query("SELECT t.ticket_id, t.event_id, t.user_id, t.booking_date, t.payment_status, e.name AS event_name, u.full_name, u.admission_number, u.department, u.email FROM tickets t JOIN events e ON t.event_id=e.id JOIN users u ON t.user_id=u.user_id ORDER BY t.booking_date DESC");
        if ($result->num_rows > 0) {
            echo '<table><tr><th>Ticket ID</th><th>Event</th><th>Participant</th><th>Admission No.</th><th>Department</th><th>Email</th><th>Booking Date</th><th>Status</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['ticket_id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['event_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['admission_number']) . '</td>';
                echo '<td>' . htmlspecialchars($row['department']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . $row['booking_date'] . '</td>';
                echo '<td>' . $row['payment_status'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No participants found.</p>';
        }
        ?>
        <p style="text-align:center;margin-top:20px;"><a href="/projects/campus_event_ticketing/app/views/admin_dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
