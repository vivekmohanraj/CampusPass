<?php
// EventController: Handles event CRUD and status updates

session_start();
require_once __DIR__ . '/../models/Event.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campus_event_ticketing';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$eventModel = new Event($conn);
$action = $_GET['action'] ?? '';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] === 'Custom' ? trim($_POST['custom_type']) : $_POST['type'];
    $data = [
        'name' => trim($_POST['name']),
        'type' => $type,
        'date' => $_POST['date'],
        'time' => $_POST['time'],
        'venue' => trim($_POST['venue']),
        'price' => floatval($_POST['price']),
        'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
        'status' => 'Upcoming'
    ];
    $eventModel->create($data);
    header('Location: /projects/campus_event_ticketing/app/views/event_list.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('DELETE FROM events WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: /projects/campus_event_ticketing/app/views/event_list.php');
    exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    // For simplicity, redirect to event_list.php (edit form can be implemented as needed)
    header('Location: /projects/campus_event_ticketing/app/views/event_list.php');
    exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $data = [
        'name' => trim($_POST['name']),
        'type' => trim($_POST['type']),
        'date' => $_POST['date'],
        'time' => $_POST['time'],
        'venue' => trim($_POST['venue']),
        'price' => floatval($_POST['price']),
        'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
        'status' => $_POST['status']
    ];
    $eventModel->update($id, $data);
    header('Location: /projects/campus_event_ticketing/app/views/event_list.php');
    exit;
}

// Fetch events for the event list page
$events = $eventModel->getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
</head>
<body>
    <div class="container">
        <h1>Event List</h1>
        <a href="/projects/campus_event_ticketing/app/views/event_form.php" class="btn">Create New Event</a>
        <a href="/projects/campus_event_ticketing/app/views/event_list.php" class="btn">Back to Event List</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Price</th>
                    <th>Max Attendees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event['id']; ?></td>
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo htmlspecialchars($event['type']); ?></td>
                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                    <td><?php echo htmlspecialchars($event['time']); ?></td>
                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                    <td><?php echo htmlspecialchars($event['price']); ?></td>
                    <td><?php echo htmlspecialchars($event['max_attendees']); ?></td>
                    <td><?php echo htmlspecialchars($event['status']); ?></td>
                    <td>
                        <a href="/projects/campus_event_ticketing/app/views/event_list.php?edit_id=<?php echo $event['id']; ?>" class="btn-edit">Edit</a>
                        <a href="/projects/campus_event_ticketing/app/controllers/EventController.php?action=delete&id=<?php echo $event['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
