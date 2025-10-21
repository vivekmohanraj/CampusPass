<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Events</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/event_list.css">
    <style>
        .event-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 20px; margin: 20px auto; max-width: 500px; }
        .event-title { font-size: 1.3em; color: var(--accent); margin-bottom: 8px; }
        .event-meta { color: #555; margin-bottom: 8px; }
        .event-status { font-weight: bold; margin-bottom: 8px; }
        .sold-out { color: #e74c3c; font-weight: bold; }
        .free { color: #27ae60; font-weight: bold; }
        .book-btn { background: var(--accent); color: #fff; border: none; padding: 8px 18px; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .book-btn:disabled { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <h2>Upcoming Events</h2>
    <?php
    require_once __DIR__ . '/../models/Event.php';
    require_once __DIR__ . '/../models/Ticket.php';
    session_start();
    $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
    $eventModel = new Event($conn);
    $ticketModel = new Ticket($conn);
    $user_id = $_SESSION['user_id'] ?? null;
    $result = $eventModel->getAllUpcoming();
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
            echo '<div class="event-meta">Type: ' . htmlspecialchars($row['type']) . ' | Date: ' . htmlspecialchars($row['date']) . ' | Time: ' . htmlspecialchars($row['time']) . '</div>';
            echo '<div class="event-meta">Venue: ' . htmlspecialchars($row['venue']) . '</div>';
            echo '<div class="event-meta">Price: ' . ($price == 0 ? '<span class="free">Free</span>' : '₹' . htmlspecialchars($price)) . '</div>';
            echo '<div class="event-meta">Tickets Remaining: ' . ($remaining === 'Unlimited' ? 'Unlimited' : max($remaining,0)) . '</div>';
            echo '<div class="event-status">Status: ' . htmlspecialchars($status) . '</div>';
            if ($status === 'Completed') {
                echo '<span class="sold-out">Event Completed</span>';
            } elseif ($remaining !== 'Unlimited' && $remaining <= 0) {
                echo '<span class="sold-out">Tickets Sold Out</span>';
            } elseif ($already_booked) {
                echo '<span class="sold-out">Already Booked</span>';
            } else {
                if ($price == 0) {
                    echo '<form action="/projects/campus_event_ticketing/app/controllers/BookingController.php?action=book" method="POST" style="margin-top:10px;">';
                    echo '<input type="hidden" name="event_id" value="' . $event_id . '"><button type="submit" class="book-btn">Book Ticket</button></form>';
                } else {
                    echo '<button class="book-btn" style="margin-top:10px;" onclick="payWithRazorpay('.$event_id.','.$price.',\'' . htmlspecialchars($row['name']) . '\')">Pay & Book Ticket</button>';
                }
            }
            echo '</div>';
        }
    } else {
        echo '<p>No upcoming events found.</p>';
    }
    ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    function payWithRazorpay(eventId, price, eventName) {
        var options = {
            key: 'rzp_test_2JkHRpx30VIAHs',
            amount: Math.round(price * 100), // in paise
            currency: 'INR',
            name: 'Campus Event Ticketing',
            description: eventName,
            handler: function (response){
                // On success, submit booking with payment_id
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/projects/campus_event_ticketing/app/controllers/BookingController.php?action=book';
                form.innerHTML = '<input type="hidden" name="event_id" value="'+eventId+'">' +
                                 '<input type="hidden" name="payment_id" value="'+response.razorpay_payment_id+'">';
                document.body.appendChild(form);
                form.submit();
            },
            theme: { color: '#2d8cf0' }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }
    </script>
</body>
</html>
