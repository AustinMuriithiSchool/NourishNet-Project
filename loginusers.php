<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check for empty fields
    if (empty($username) || empty($password)) {
        die("Please fill in all fields.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT user_id, password, user_type FROM users WHERE username = ?");
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $user_type);

    // Check if user exists and verify password
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['session_id'] = session_id();

            // Insert or update the session in the database
            $session_id = session_id();
            $sql = "INSERT INTO sessions (session_id, user_id) VALUES ('$session_id', '$user_id')
                    ON DUPLICATE KEY UPDATE last_activity = CURRENT_TIMESTAMP";
            if ($conn->query($sql) !== TRUE) {
                die("Error updating session: " . $conn->error);
            }

            // Redirect based on user type
            if ($user_type == "admin") {
                header("Location: admindashboard.php");
            } else {
                header("Location: userdashboard.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            max-width: 400px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .textbox {
            margin-bottom: 20px;
        }

        .textbox label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .textbox input[type="text"],
        .textbox input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            background: #29d978;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
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

        .register-link {
            margin-top: 20px;
            text-align: center;
        }

        .register-link a {
            color: #29d978;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #25c167;
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
        <div class="container">
            <div class="login-box">
                <h2>Login</h2>
                <form method="post" action="loginusers.php">
                    <div class="textbox">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username">
                    </div>
                    <div class="textbox">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <input type="submit" class="btn" value="Login">
                </form>
                <div class="register-link">
                <a href="registerusers.php">New user? Please sign up here!</a>
            </div>
            </div>
        </div>
    </div>
</body>
</html>

