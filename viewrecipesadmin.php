<?php
session_start();
include 'config.php';

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add filtering conditions
$rating_filter = isset($_GET['rating']) ? $_GET['rating'] : 'highest';
$recipe_tag_filter = isset($_GET['recipe_tag']) ? $_GET['recipe_tag'] : 'all';

$order_by = $rating_filter == 'lowest' ? 'ASC' : 'DESC';
$recipe_tag_condition = $recipe_tag_filter != 'all' ? "WHERE recipe.recipe_tag = '$recipe_tag_filter'" : '';

$sql = "SELECT recipe.recipe_id, recipe.recipe, recipe.recipe_image, recipe.recipe_tag, users.username, AVG(rating.rating) AS avg_rating
        FROM recipe
        JOIN users ON recipe.user_id = users.user_id
        LEFT JOIN rating ON recipe.recipe_id = rating.recipe_id
        $recipe_tag_condition
        GROUP BY recipe.recipe_id
        ORDER BY avg_rating $order_by";
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

        .filters {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .filters form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filters select, .filters input[type="submit"] {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .recipes-container {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 20px;
        }

        .recipe {
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .recipe img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
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
            <a href="admindashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>View Recipes</h2>
        
        <div class="filters">
            <form method="GET" action="">
                <label for="rating">Filter by Rating:</label>
                <select name="rating" id="rating">
                    <option value="highest">Highest Rated</option>
                    <option value="lowest">Lowest Rated</option>
                </select>

                <label for="recipe_tag">Filter by Recipe Tag:</label>
                <select name="recipe_tag" id="recipe_tag">
                    <option value="all">All</option>
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Snack">Snack</option>
                </select>

                <input type="submit" value="Filter">
            </form>
        </div>

        <div class="recipes-container">
            <?php
            // Display recipe names, images, usernames, and average ratings
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='recipe'>";
                    echo "<h3>".$row["recipe"]."</h3>";
                    echo '<img src="'.$row["recipe_image"].'" alt="'.$row["recipe"].'">';
                    echo "<p>Added by: ".$row["username"]."</p>";
                    echo "<p>Average Rating: ";
                    echo number_format($row["avg_rating"], 1); // Display average rating with one decimal place
                    echo "</p>";
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

<?php
$conn->close();
?>
