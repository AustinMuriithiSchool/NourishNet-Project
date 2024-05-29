<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background: #f4f4f4;
    margin: 0;
    padding: 0;
}

.taskbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #0072ff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.taskbar-left button,
.taskbar-right button {
    background: #005bb5;
    color: white;
    border: none;
    padding: 10px 15px;
    margin: 0 5px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.taskbar-left button:hover,
.taskbar-right button:hover {
    background: #004a8a;
}

.content {
    margin-top: 70px;
    padding: 20px;
}

h1 {
    color: #333;
}

p {
    color: #666;
}</style>
</head>
<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <button onclick="location.href='userdashboard.php'">Dashboard</button>
            <button onclick="location.href='profile.php'">Profile</button>
            <button onclick="location.href='settings.php'">Settings</button>
        </div>
        <div class="taskbar-right">
            <button onclick="location.href='editusers.php?username=<?php echo $username; ?>'">Edit Profile</button>
        </div>
    </div>
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>This is your user dashboard. Here you can view your profile, adjust settings, and manage your activities.</p>
    </div>
</body>
</html>
