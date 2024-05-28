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
    <style> /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #0072ff, #00c6ff);
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
    color: #333;
}

.textbox {
    margin-bottom: 20px;
    position: relative;
}

.textbox label {
    display: block;
    text-align: left;
    margin-bottom: 5px;
    color: #555;
}

.textbox input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.btn {
    width: 100%;
    padding: 10px;
    background: #0072ff;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn:hover {
    background: #005bb5;
}
</style>
</head>
<body>
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
        </div>
    </div>
</body>
</html>
