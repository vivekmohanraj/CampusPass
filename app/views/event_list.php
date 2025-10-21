<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/event_list.css">
</head>
<body>
    <div class="event-container">
        <div class="event-header">
            <h2>Event Management</h2>
        </div>
        <div class="event-form">
            <h3>Create New Event</h3>
            <form action="/projects/campus_event_ticketing/app/controllers/EventController.php?action=create" method="POST">
                <label>Event Name:</label><input type="text" name="name" required>
                <label>Event Type:</label>
                <select name="type" id="event_type_select" onchange="toggleCustomType()">
                    <option value="Seminar">Seminar</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Sports">Sports</option>
                    <option value="Custom">Custom Type</option>
                </select>
                <input type="text" name="custom_type" id="custom_type_input" style="display:none;" placeholder="Enter custom type">
                <label>Date:</label><input type="date" name="date" required>
                <label>Time:</label><input type="time" name="time" required>
                <label>Venue:</label><input type="text" name="venue" required>
                <label>Ticket Price (₹):</label><input type="number" name="price" min="0" step="0.01" required>
                <label>Max Attendees:</label><input type="number" name="max_attendees" min="0">
                <button type="submit">Create Event</button>
            </form>
        </div>
        <h3>All Events</h3>
        <div id="event-list">
            <?php
            require_once __DIR__ . '/../models/Event.php';
            $conn = new mysqli('localhost', 'root', '', 'campus_event_ticketing');
            $eventModel = new Event($conn);
            $result = $eventModel->getAllUpcoming();
            if ($result->num_rows > 0) {
                echo '<table class="event-table"><tr><th>Name</th><th>Type</th><th>Date</th><th>Time</th><th>Venue</th><th>Price</th><th>Status</th><th>Actions</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['type']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['time']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['venue']) . '</td>';
                    echo '<td>₹' . htmlspecialchars($row['price']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td class="event-actions"><a href="/projects/campus_event_ticketing/app/views/event_list.php?edit_id=' . $row['id'] . '">Edit</a> | <a href="/projects/campus_event_ticketing/app/controllers/EventController.php?action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Delete this event?\')">Delete</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No events found.</p>';
            }

            if (isset($_GET['edit_id'])) {
                $edit_id = intval($_GET['edit_id']);
                $edit_event = $eventModel->findById($edit_id);
                if ($edit_event) {
                    echo '<div class="event-form"><h3>Edit Event</h3>';
                    echo '<form action="/projects/campus_event_ticketing/app/controllers/EventController.php?action=update" method="POST">';
                    echo '<input type="hidden" name="id" value="' . $edit_event['id'] . '">';
                    echo '<label>Event Name:</label><input type="text" name="name" value="' . htmlspecialchars($edit_event['name']) . '" required>';
                    echo '<label>Event Type:</label><input type="text" name="type" value="' . htmlspecialchars($edit_event['type']) . '" required>';
                    echo '<label>Date:</label><input type="date" name="date" value="' . htmlspecialchars($edit_event['date']) . '" required>';
                    echo '<label>Time:</label><input type="time" name="time" value="' . htmlspecialchars($edit_event['time']) . '" required>';
                    echo '<label>Venue:</label><input type="text" name="venue" value="' . htmlspecialchars($edit_event['venue']) . '" required>';
                    echo '<label>Ticket Price (₹):</label><input type="number" name="price" min="0" step="0.01" value="' . htmlspecialchars($edit_event['price']) . '" required>';
                    echo '<label>Max Attendees:</label><input type="number" name="max_attendees" min="0" value="' . htmlspecialchars($edit_event['max_attendees']) . '">';
                    echo '<label>Status:</label><select name="status"><option value="Upcoming"' . ($edit_event['status'] == 'Upcoming' ? ' selected' : '') . '>Upcoming</option><option value="Completed"' . ($edit_event['status'] == 'Completed' ? ' selected' : '') . '>Completed</option></select>';
                    echo '<button type="submit">Update Event</button></form></div>';
                }
            }
            ?>
        </div>
    </div>
    <script>
    function toggleCustomType() {
        var select = document.getElementById('event_type_select');
        var input = document.getElementById('custom_type_input');
        if (select.value === 'Custom') {
            input.style.display = 'inline';
            input.required = true;
        } else {
            input.style.display = 'none';
            input.required = false;
        }
    }
    </script>
</body>
</html>
