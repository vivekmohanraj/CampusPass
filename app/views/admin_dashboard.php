<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/admin_dashboard.css">
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
            <h2>Admin Dashboard</h2>
            <nav class="dashboard-nav">
                <a href="/projects/campus_event_ticketing/app/views/event_list.php">Manage Events</a>
                <a href="/projects/campus_event_ticketing/app/views/participants.php">View Participants</a>
                <a href="/projects/campus_event_ticketing/app/views/payments.php">View Payments</a>
                <a href="/projects/campus_event_ticketing/logout.php">Logout</a>
            </nav>
        </div>
        <?php
        $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
        $events = $conn->query("SELECT COUNT(*) as cnt FROM events")->fetch_assoc()['cnt'];
        $participants = $conn->query("SELECT COUNT(*) as cnt FROM tickets")->fetch_assoc()['cnt'];
        $payments = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status='Success'")->fetch_assoc()['total'] ?? 0;
        ?>
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-title">Total Events</div>
                <div class="stat-value"><?php echo $events; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Participants</div>
                <div class="stat-value"><?php echo $participants; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Payments (₹)</div>
                <div class="stat-value">₹<?php echo number_format($payments,2); ?></div>
            </div>
        </div>
        <div class="dashboard-welcome">
            <p>Welcome, <b>Admin</b>!<br>
            Use the navigation above to manage events, view participants, and track payments.<br>
            <span style="color:var(--accent);font-weight:bold;font-size:1.1em;">Campus Event Ticketing System</span></p>
        </div>
    </div>
</body>
</html>
