<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginusers.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (empty($username) || empty($email)) {
        die("Please fill in all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $query = "UPDATE users SET username=?, email=?";
    if ($password) {
        $query .= ", password=?";
    }
    $query .= " WHERE user_id=?";

    $stmt = $conn->prepare($query);
    if ($password) {
        $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: userdashboard.php");  // Redirect to dashboard after update
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        .container {
            margin-top: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .edit-box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .textbox {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background: #29d978;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #25c167;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-box">
            <h2>Edit Profile</h2>
            <form method="post" action="editusers.php">
                <div class="textbox">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div class="textbox">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="textbox">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" id="password" name="password">
                </div>
                <input type="submit" class="btn" value="Update">
            </form>
        </div>
    </div>
</body>
</html>
