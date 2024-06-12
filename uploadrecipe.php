<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to upload a recipe.";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe = $_POST['recipe'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $tag = $_POST['tag'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO recipe (user_id, recipe, recipe_description, recipe_ingredients, recipe_instructions, recipe_tag, recipe_image) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $recipe, $description, $ingredients, $instructions, $tag, $image);
        
        if ($stmt->execute()) {
            echo "New recipe uploaded successfully";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Recipe</title>
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

        .content h2{
            color: #29d978;
        }

        .content label{
            color: black;
        }

        .taskbar-left h1 {
            color: white;
            margin-right: 20px;
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

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
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
            <button onclick="location.href='userdashboard.php'">Dashboard</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
    <div class="content">
        <h2>Upload Recipe</h2>
        <form action="uploadrecipe.php" method="post" enctype="multipart/form-data">
            <label for="recipe">Recipe Name:</label>
            <input type="text" id="recipe" name="recipe" required><br>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>
            
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required></textarea><br>
            
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" required></textarea><br>

            <label for="tag">Recipe Type:</label>
            <textarea id="tag" name="tag" required></textarea><br>
            
            <label for="image">Recipe Image:</label>
            <input type="file" id="image" name="image"><br>
            
            <input type="submit" value="Upload Recipe">
        </form>
    </div>
</body>
</html>