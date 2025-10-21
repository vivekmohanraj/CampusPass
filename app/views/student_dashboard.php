<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <style>
        body { background: var(--bg); }
        .dashboard-container { max-width: 1100px; margin: 40px auto; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .dashboard-header h2 { margin: 0; color: var(--accent); font-size: 2.2em; letter-spacing: 1px; }
        .dashboard-nav { display: flex; gap: 24px; }
        .dashboard-nav a { background: linear-gradient(90deg, var(--accent), var(--accent-dark)); color: #fff; padding: 14px 32px; border-radius: 8px; font-weight: bold; font-size: 17px; text-decoration: none; box-shadow: 0 2px 8px #0002; transition: background 0.2s, transform 0.2s; }
        .dashboard-nav a:hover { background: linear-gradient(90deg, var(--accent-dark), var(--accent)); transform: translateY(-2px) scale(1.04); }
        .dashboard-welcome { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 40px; text-align: center; font-size: 1.25em; color: #2d3e50; margin-bottom: 32px; }
        .dashboard-stats { display: flex; gap: 32px; justify-content: center; margin-bottom: 32px; }
        .stat-card { background: linear-gradient(120deg, var(--accent-light), #fff 80%); border-radius: 10px; box-shadow: 0 2px 10px #0001; padding: 32px 40px; min-width: 200px; text-align: center; }
        .stat-title { font-size: 1.1em; color: var(--accent-dark); margin-bottom: 8px; }
        .stat-value { font-size: 2.1em; color: var(--accent); font-weight: bold; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Student Dashboard</h2>
            <nav class="dashboard-nav">
                <a href="/projects/campus_event_ticketing/app/views/student_events.php">Browse Events</a>
                <a href="/projects/campus_event_ticketing/app/views/my_tickets.php">My Tickets</a>
                <a href="/projects/campus_event_ticketing/logout.php">Logout</a>
            </nav>
        </div>
        <?php
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $events = $conn->query("SELECT COUNT(*) as cnt FROM events WHERE status='Upcoming'")->fetch_assoc()['cnt'];
        $my_tickets = $user_id ? $conn->query("SELECT COUNT(*) as cnt FROM tickets WHERE user_id=$user_id")->fetch_assoc()['cnt'] : 0;
        $paid = $user_id ? $conn->query("SELECT SUM(amount) as total FROM payments WHERE user_id=$user_id AND status='Success'")->fetch_assoc()['total'] ?? 0 : 0;
        ?>
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-title">Upcoming Events</div>
                <div class="stat-value"><?php echo $events; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">My Tickets</div>
                <div class="stat-value"><?php echo $my_tickets; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Paid (₹)</div>
                <div class="stat-value">₹<?php echo number_format($paid,2); ?></div>
            </div>
        </div>
        <div class="dashboard-welcome">
            <p>Welcome, <b>Student</b>!<br>
            Use the navigation above to browse and book events, and view your tickets.<br>
            <span style="color:var(--accent);font-weight:bold;font-size:1.1em;">Campus Event Ticketing System</span></p>
        </div>
    </div>
</body>
</html>
