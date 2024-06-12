<?php
session_start();
include 'config.php';

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve recipe data from the database
$sql = "SELECT recipe.recipe_id, recipe.recipe, recipe.recipe_image, users.username 
        FROM recipe 
        JOIN users ON recipe.user_id = users.user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Recipes</title>
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

        h2 {
            color: #29d978;
            text-align: center;
            margin-bottom: 20px;
        }

        .recipes-container {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 20px;
        }

        .recipe {
            min-width: 300px;
            border: 1px solid #ccc;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .recipe img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .recipe h3 {
            color: black; 
            margin-bottom: 10px;
        }

        .recipe p {
            color: #666;
            margin-bottom: 10px;
        }

        .recipe form {
            text-align: center;
        }

        .recipe form input[type="submit"] {
            background: #29d978;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .recipe form input[type="submit"]:hover {
            background: #005bb5;
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
            <a href="userdashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>View Recipes</h2>
    
        <div class="recipes-container">
            <?php
            // Display recipe names, images, and the user who added them
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='recipe'>";
                    echo "<h3>".$row["recipe"]."</h3>";
                    echo '<img src="'.$row["recipe_image"].'" alt="'.$row["recipe"].'">';
                    echo "<p>Added by: ".$row["username"]."</p>";
                    echo "<form action='viewrecipe.php' method='GET'>";
                    echo "<input type='hidden' name='recipe_id' value='".$row["recipe_id"]."'>";
                    echo "<input type='submit' value='View Details'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No recipes found</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
