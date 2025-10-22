<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <style>
        body { background: var(--bg-secondary); min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); border-radius: var(--radius-xl); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-lg); color: white; text-align: center; }
        .page-header h2 { margin: 0; color: white; font-size: 2rem; font-weight: 700; }
        .table-box { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 2rem; overflow-x: auto; }
        .back-link { display: inline-block; margin-top: 1.5rem; color: #8b5cf6; font-weight: 600; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        @media (max-width: 768px) { .container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>Payments</h2>
        </div>
        <div class="table-box">
        <?php
        require_once __DIR__ . '/../models/Payment.php';
        require_once __DIR__ . '/../models/Event.php';
        require_once __DIR__ . '/../models/User.php';
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $paymentModel = new Payment($conn);
        $eventModel = new Event($conn);
        $userModel = new User($conn);
        $result = $conn->query("SELECT p.payment_id, p.event_id, p.user_id, p.amount, p.status, p.created_at, e.name AS event_name, u.full_name, u.email FROM payments p JOIN events e ON p.event_id=e.id JOIN users u ON p.user_id=u.user_id ORDER BY p.created_at DESC");
        if ($result->num_rows > 0) {
            echo '<table><tr><th>Payment ID</th><th>Event</th><th>User</th><th>Email</th><th>Amount (₹)</th><th>Status</th><th>Date</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['payment_id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['event_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . $row['amount'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>' . $row['created_at'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No payments found.</p>';
        }
        ?>
            <p class="back-link" style="text-align:center;"><a href="/projects/campus_event_ticketing/app/views/admin_dashboard.php" class="back-link">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
