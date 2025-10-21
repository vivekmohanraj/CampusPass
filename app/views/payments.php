<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>
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
        <h2>Payments</h2>
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
        <p style="text-align:center;margin-top:20px;"><a href="/projects/campus_event_ticketing/app/views/admin_dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
