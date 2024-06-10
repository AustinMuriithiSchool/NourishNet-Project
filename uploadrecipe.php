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
</head>
<body>
    <h2>Upload Recipe</h2>
    <form action="uploadrecipe.php" method="post" enctype="multipart/form-data">
        <label for="recipe">Recipe Name:</label>
        <input type="text" id="recipe" name="recipe" required><br><br>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea><br><br>
        
        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" required></textarea><br><br>
        
        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" required></textarea><br><br>

        <label for="tag">Recipe Type:</label>
        <textarea id="tag" name="tag" required></textarea><br><br>
        
        <label for="image">Recipe Image:</label>
        <input type="file" id="image" name="image"><br><br>
        
        <input type="submit" value="Upload Recipe">
    </form>
</body>
</html>

