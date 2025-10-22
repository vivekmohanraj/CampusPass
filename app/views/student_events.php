<?php
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Ticket.php';
session_start();
$conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
$eventModel = new Event($conn);
$ticketModel = new Ticket($conn);
$user_id = $_SESSION['user_id'] ?? null;
$result = $eventModel->getAllUpcoming();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/event_list.css">
    <style>
        body {
            background: var(--bg-secondary);
            min-height: 100vh;
        }
        
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            color: white;
            text-align: center;
        }
        
        .page-header h2 {
            margin: 0;
            color: white;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .page-header p {
            margin: 0.5rem 0 0 0;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .event-card { 
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.75rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--accent);
        }
        
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .event-title { 
            font-size: 1.5rem;
            color: var(--text);
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        
        .event-meta { 
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }
        
        .event-meta svg {
            width: 18px;
            height: 18px;
            color: var(--accent);
        }
        
        .event-status { 
            font-weight: 600;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            display: inline-block;
            font-size: 0.875rem;
        }
        
        .status-upcoming {
            background: var(--accent-light);
            color: var(--accent);
        }
        
        .sold-out { 
            color: var(--error);
            font-weight: 700;
            background: #fee2e2;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            display: inline-block;
        }
        
        .free { 
            color: var(--success);
            font-weight: 700;
            background: #d1fae5;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
            display: inline-block;
            font-size: 0.875rem;
        }
        
        .price-badge {
            background: var(--accent-light);
            color: var(--accent);
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
            display: inline-block;
            font-weight: 700;
            font-size: 0.875rem;
        }
        
        .book-btn { 
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .book-btn:disabled { 
            background: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .event-divider {
            height: 1px;
            background: var(--border);
            margin: 1rem 0;
        }
        
        .no-events {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        
        .no-events p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .events-container {
                padding: 1rem;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="events-container">
        <div class="page-header">
            <h2>Upcoming Events</h2>
            <p>Discover and book exciting campus events</p>
        </div>
        <div class="events-grid">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $event_id = $row['id'];
            $max = $row['max_attendees'];
            $price = $row['price'];
            $status = $row['status'];
            $tickets_q = $conn->query("SELECT COUNT(*) as cnt FROM tickets WHERE event_id=$event_id");
            $tickets_count = $tickets_q->fetch_assoc()['cnt'];
            $remaining = ($max && $max > 0) ? ($max - $tickets_count) : 'Unlimited';
            $already_booked = $user_id ? $ticketModel->findByUserAndEvent($user_id, $event_id) : false;
            echo '<div class="event-card">';
            echo '<div class="event-title">' . htmlspecialchars($row['name']) . '</div>';
            echo '<div class="event-meta"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>' . htmlspecialchars($row['type']) . ' • ' . htmlspecialchars($row['date']) . ' • ' . htmlspecialchars($row['time']) . '</div>';
            echo '<div class="event-meta"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>' . htmlspecialchars($row['venue']) . '</div>';
            echo '<div class="event-meta"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>' . ($price == 0 ? '<span class="free">Free Event</span>' : '<span class="price-badge">₹' . htmlspecialchars($price) . '</span>') . '</div>';
            echo '<div class="event-meta"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Tickets: ' . ($remaining === 'Unlimited' ? 'Unlimited' : max($remaining,0)) . '</div>';
            echo '<div class="event-divider"></div>';
            echo '<div class="event-status status-upcoming">Status: ' . htmlspecialchars($status) . '</div>';
            if ($status === 'Completed') {
                echo '<span class="sold-out">Event Completed</span>';
            } elseif ($remaining !== 'Unlimited' && $remaining <= 0) {
                echo '<span class="sold-out">Tickets Sold Out</span>';
            } elseif ($already_booked) {
                echo '<span class="sold-out">Already Booked</span>';
            } else {
                if ($price == 0) {
                    echo '<form action="/projects/campus_event_ticketing/app/controllers/BookingController.php?action=book" method="POST">';
                    echo '<input type="hidden" name="event_id" value="' . $event_id . '"><button type="submit" class="book-btn">Book Free Ticket</button></form>';
                } else {
                    echo '<button class="book-btn" onclick="payWithRazorpay('.$event_id.','.$price.',\'' . htmlspecialchars($row['name']) . '\')">Pay ₹' . htmlspecialchars($price) . ' & Book</button>';
                }
            }
            echo '</div>';
        }
    } else {
        echo '<div class="no-events"><p>No upcoming events found. Check back later!</p></div>';
    }
    ?>
        </div>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    function payWithRazorpay(eventId, price, eventName) {
        var options = {
            key: 'rzp_test_2JkHRpx30VIAHs',
            amount: Math.round(price * 100),
            currency: 'INR',
            name: 'CampusPass',
            description: eventName,
            order_id: '',
            handler: function (response){
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/projects/campus_event_ticketing/app/controllers/BookingController.php?action=book';
                form.innerHTML = '<input type="hidden" name="event_id" value="'+eventId+'">' +
                                 '<input type="hidden" name="payment_id" value="'+response.razorpay_payment_id+'">';
                document.body.appendChild(form);
                form.submit();
            },
            prefill: {
                name: '',
                email: '',
                contact: ''
            },
            notes: {
                event_id: eventId
            },
            theme: { 
                color: '#2563eb'
            },
            modal: {
                ondismiss: function(){
                    console.log('Payment cancelled');
                }
            }
        };
        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function (response){
            alert('Payment failed: ' + response.error.description);
        });
        rzp.open();
    }
    </script>
</body>
</html>
