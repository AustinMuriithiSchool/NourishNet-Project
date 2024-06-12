<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $email = htmlspecialchars(trim($_POST['email']));
    $user_type = htmlspecialchars(trim($_POST['user_type']));

    // Check for empty fields
    if (empty($username) || empty($password) || empty($email) || empty($user_type)) {
        die("Please fill in all fields.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $user_type);

    // Execute the statement
    if ($stmt->execute()) {
        // Start session and set session variables
        session_start();
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user_type;

        // Redirect based on user type
        if ($user_type == 'admin') {
            header("Location: admindashboard.php");
        } else {
            header("Location: userdashboard.php");
        }
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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

        .taskbar-left {
            display: flex;
            align-items: center;
        }

        .taskbar-left h1 {
            color: white;
            margin-right: 20px;
        }

        .taskbar-right a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
            transition: color 0.3s;
        }

        .taskbar-right a:hover {
            color: #004a8a;
        }

        .content {
            margin-top: 70px;
            padding: 20px;
        }

        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .register-container h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .register-container label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-container input[type="submit"] {
            width: 100%;
            background: #29d978;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-container input[type="submit"]:hover {
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
            <h1>NourishNet</h1>
        </div>
        <div class="taskbar-right">
            
        </div>
    </div>

    <div class="content">
        <div class="register-container">
            <h2>Register</h2>
            <form method="post" action="registerusers.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email">
                <label for="user_type">User Type:</label>
                <select id="user_type" name="user_type">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>
</html>
 
