<?php
session_start();
include 'config.php';

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if recipe ID is provided
if(isset($_GET['recipe_id']) && !empty($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];
    
    // Retrieve recipe details from the database
    $sql = "SELECT * FROM recipe WHERE recipe_id = $recipe_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recipe_name = $row["recipe"];
        $description = $row["recipe_description"];
        $ingredients = $row["recipe_ingredients"];
        $instructions = $row["recipe_instructions"];
        $tag = $row["recipe_tag"];
        $image = $row["recipe_image"];
    } else {
        echo "Recipe not found";
        exit;
    }
} else {
    echo "Recipe ID not provided";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $recipe_name; ?></title>
</head>
<body>
    <h2><?php echo $recipe_name; ?></h2>
    
    <img src="<?php echo $image; ?>" alt="<?php echo $recipe_name; ?>"><br><br>
    
    <h3>Description:</h3>
    <p><?php echo $description; ?></p>
    
    <h3>Ingredients:</h3>
    <p><?php echo $ingredients; ?></p>
    
    <h3>Instructions:</h3>
    <p><?php echo $instructions; ?></p>
    
    <h3>Recipe Type:</h3>
    <p><?php echo $tag; ?></p>

    <a href="viewrecipes.php">Back to Recipes</a>

</body>
</html>
