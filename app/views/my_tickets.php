<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tickets</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/student_dashboard.css">
    <style>
        .table-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 24px; margin: 32px auto; max-width: 900px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; }
        th { background: var(--accent); color: #fff; }
    </style>
</head>
<body>
    <div class="table-box">
        <h2>My Tickets</h2>
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
        <p style="text-align:center;margin-top:20px;"><a href="/projects/campus_event_ticketing/app/views/student_dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
