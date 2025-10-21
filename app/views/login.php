<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/login.css">
</head>
<body>
    <h2>Login</h2>
    <form action="/projects/campus_event_ticketing/app/controllers/AuthController.php?action=login" method="POST">
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Password:</label><input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="/projects/campus_event_ticketing/app/views/register.php">Register here</a></p>
</body>
</html>
