<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: loginusers.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style> 
                * {
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
            background: #29d978;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .taskbar-left h1 {
            color: white;
            margin-right: 20px;
        }

        .taskbar-left,
        .taskbar-right {
            display: flex;
            align-items: center;
        }

        .taskbar-left button,
        .taskbar-right button {
            background: transparent;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 0 5px;
            cursor: pointer;
            transition: color 0.3s;
            font-size: 16px;
        }

        .taskbar-left button:hover,
        .taskbar-right button:hover {
            color: #004a8a;
        }

        .content {
            margin-top: 70px;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        .content button {
            background: #29d978;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .content button:hover {
            background: #25c167;
        }

        /* Custom scrollbar styles */
        ::-webkit-scrollbar {
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f4f4f4;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #29d978;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #66cc66;
        }

    </style>
</head>
<body>
    <div class="taskbar">
        <div class="taskbar-left">
            <button onclick="location.href='admindashboard.php'">Dashboard</button>
            <button onclick="location.href='manageusers.php'">Manage Users</button>
            <button onclick="location.href='reports.php'">Reports</button>
        </div>
        <div class="taskbar-right">
            <button onclick="location.href='editadmins.php?username=<?php echo $username; ?>'">Edit Profile</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>This is the admin dashboard. Here you can manage users, view reports, and perform other administrative tasks.</p>
    </div>
</body>
</html>
