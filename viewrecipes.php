<?php
session_start();
include 'config.php';

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve recipe data from the database
$sql = "SELECT recipe_id, recipe, recipe_image FROM recipe";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Recipes</title>
</head>
<body>
    <h2>View Recipes</h2>
    
    <?php
    // Display recipe names and images
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>".$row["recipe"]."</h3>";
            echo '<img src="'.$row["recipe_image"].'" alt="'.$row["recipe"].'">';
            echo "<form action='viewrecipe.php' method='GET'>";
            echo "<input type='hidden' name='recipe_id' value='".$row["recipe_id"]."'>";
            echo "<input type='submit' value='View Details'>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No recipes found";
    }
    ?>

</body>
</html>
