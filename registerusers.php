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
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .register-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .register-container input, .register-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .register-container input[type="submit"] {
            background: #0072ff;
            border: none;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        .register-container input[type="submit"]:hover {
            background: #005bb5;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="post" action="registerusers.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email"><br>
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
