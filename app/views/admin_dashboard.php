<?php
$conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
$events = $conn->query("SELECT COUNT(*) as cnt FROM events")->fetch_assoc()['cnt'];
$participants = $conn->query("SELECT COUNT(*) as cnt FROM tickets")->fetch_assoc()['cnt'];
$payments = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status='Success'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/admin_dashboard.css">
    <style>
        body { 
            background: var(--bg-secondary);
            min-height: 100vh;
        }
        
        .dashboard-container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 2rem;
        }
        
        .dashboard-header { 
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            color: white;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .header-title h1 { 
            margin: 0; 
            color: white;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .header-title p {
            margin: 0.5rem 0 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }
        
        .dashboard-nav { 
            display: flex; 
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .dashboard-nav a { 
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .dashboard-nav a:hover { 
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            text-decoration: none;
        }
        
        .dashboard-stats { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card { 
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            transition: all 0.3s ease;
            border-left: 4px solid #8b5cf6;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ede9fe 0%, #ede9fe 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b5cf6;
        }
        
        .stat-title { 
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .stat-value { 
            font-size: 2.5rem;
            color: var(--text);
            font-weight: 700;
            line-height: 1;
        }
        
        .dashboard-welcome { 
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            text-align: center;
        }
        
        .welcome-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
        }
        
        .dashboard-welcome h2 {
            color: var(--text);
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }
        
        .dashboard-welcome p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .brand-text {
            color: #8b5cf6;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .dashboard-nav {
                width: 100%;
                flex-direction: column;
            }
            
            .dashboard-nav a {
                width: 100%;
                text-align: center;
            }
            
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            
            .stat-value {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>Admin Dashboard</h1>
                    <p>Manage your events and track performance</p>
                </div>
                <nav class="dashboard-nav">
                    <a href="/projects/campus_event_ticketing/app/views/event_list.php">Manage Events</a>
                    <a href="/projects/campus_event_ticketing/app/views/participants.php">View Participants</a>
                    <a href="/projects/campus_event_ticketing/app/views/payments.php">View Payments</a>
                    <a href="/projects/campus_event_ticketing/logout.php">Logout</a>
                </nav>
            </div>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="stat-title">Total Events</div>
                </div>
                <div class="stat-value"><?php echo $events; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-title">Total Participants</div>
                </div>
                <div class="stat-value"><?php echo $participants; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="stat-title">Total Revenue</div>
                </div>
                <div class="stat-value">₹<?php echo number_format($payments, 2); ?></div>
            </div>
        </div>
        
        <div class="dashboard-welcome">
            <div class="welcome-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <h2>Welcome, Admin!</h2>
            <p>
                Use the navigation above to manage events, view participants, and track payments.<br>
                <span class="brand-text">CampusPass - Event Management System</span>
            </p>
        </div>
    </div>
</body>
</html>
