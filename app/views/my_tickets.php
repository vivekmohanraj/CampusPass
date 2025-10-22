<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <style>
        body { background: var(--bg-secondary); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); border-radius: var(--radius-xl); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-lg); color: white; text-align: center; }
        .page-header h2 { margin: 0; color: white; font-size: 2rem; font-weight: 700; }
        .table-box { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 2rem; }
        .back-link { display: inline-block; margin-top: 1.5rem; color: var(--accent); font-weight: 600; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        @media (max-width: 768px) { .container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>My Tickets</h2>
        </div>
        <div class="table-box">
        <?php
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            echo '<p style="color:red;">You must be logged in to view your tickets.</p>';
            exit;
        }
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $result = $conn->query("SELECT t.ticket_id, t.event_id, t.booking_date, t.payment_status, e.name AS event_name, e.date, e.time, e.venue FROM tickets t JOIN events e ON t.event_id=e.id WHERE t.user_id=$user_id ORDER BY t.booking_date DESC");
        if ($result->num_rows > 0) {
            echo '<table><tr><th>Ticket ID</th><th>Event</th><th>Date</th><th>Time</th><th>Venue</th><th>Booking Date</th><th>Status</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['ticket_id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['event_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['venue']) . '</td>';
                echo '<td>' . $row['booking_date'] . '</td>';
                echo '<td>' . $row['payment_status'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No tickets found.</p>';
        }
        ?>
            <p class="back-link" style="text-align:center;"><a href="/projects/campus_event_ticketing/app/views/student_dashboard.php" class="back-link">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
