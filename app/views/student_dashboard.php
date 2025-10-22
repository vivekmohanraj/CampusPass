<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
$conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
$events = $conn->query("SELECT COUNT(*) as cnt FROM events WHERE status='Upcoming'")->fetch_assoc()['cnt'];
$my_tickets = $user_id ? $conn->query("SELECT COUNT(*) as cnt FROM tickets WHERE user_id=$user_id")->fetch_assoc()['cnt'] : 0;
$paid = $user_id ? $conn->query("SELECT SUM(amount) as total FROM payments WHERE user_id=$user_id AND status='Success'")->fetch_assoc()['total'] ?? 0 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
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
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
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
            border-left: 4px solid var(--accent);
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
            background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent-light) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
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
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
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
            color: var(--accent);
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
                    <h1>Student Dashboard</h1>
                    <p>Welcome back! Here's your overview</p>
                </div>
                <nav class="dashboard-nav">
                    <a href="/projects/campus_event_ticketing/app/views/student_events.php">Browse Events</a>
                    <a href="/projects/campus_event_ticketing/app/views/my_tickets.php">My Tickets</a>
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
                    <div class="stat-title">Upcoming Events</div>
                </div>
                <div class="stat-value"><?php echo $events; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline>
                            <polyline points="7.5 19.79 7.5 14.6 3 12"></polyline>
                            <polyline points="21 12 16.5 14.6 16.5 19.79"></polyline>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="stat-title">My Tickets</div>
                </div>
                <div class="stat-value"><?php echo $my_tickets; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="stat-title">Total Paid</div>
                </div>
                <div class="stat-value">₹<?php echo number_format($paid, 2); ?></div>
            </div>
        </div>
        
        <div class="dashboard-welcome">
            <div class="welcome-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h2>Welcome, Student!</h2>
            <p>
                Use the navigation above to browse and book events, and view your tickets.<br>
                <span class="brand-text">CampusPass - Your Event Companion</span>
            </p>
        </div>
    </div>
</body>
</html>
