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
    
    // Fetch all ratings and comments for the recipe
    $sql_ratings = "SELECT r.rating, r.rating_date, r.rating_comment, u.username 
                    FROM rating r 
                    JOIN users u ON r.user_id = u.user_id 
                    WHERE r.recipe_id = $recipe_id";
    $result_ratings = $conn->query($sql_ratings);
    $ratings = [];
    if ($result_ratings->num_rows > 0) {
        while ($row = $result_ratings->fetch_assoc()) {
            $ratings[] = $row;
        }
    }

    // Calculate average rating
    $sql_avg_rating = "SELECT AVG(rating) as avg_rating FROM rating WHERE recipe_id = $recipe_id";
    $result_avg_rating = $conn->query($sql_avg_rating);
    $avg_rating = 0;
    if ($result_avg_rating->num_rows > 0) {
        $row_avg = $result_avg_rating->fetch_assoc();
        $avg_rating = round($row_avg['avg_rating'], 2);
    }

    // Handle rating and comment submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
        $user_id = $_SESSION['user_id'];  // Assuming user is logged in and user_id is stored in session
        $rating = intval($_POST['rating']);
        $rating_comment = $_POST['rating_comment']; // Get rating comment
        
        // Check if user has already rated this recipe
        $sql_check = "SELECT * FROM rating WHERE user_id = $user_id AND recipe_id = $recipe_id";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // Update existing rating and comment
            $sql_update = "UPDATE rating SET rating = $rating, rating_comment = '$rating_comment', rating_date = NOW() WHERE user_id = $user_id AND recipe_id = $recipe_id";
            $conn->query($sql_update);
        } else {
            // Insert new rating and comment
            $sql_insert = "INSERT INTO rating (user_id, recipe_id, rating, rating_comment, rating_date) VALUES ($user_id, $recipe_id, $rating, '$rating_comment', NOW())";
            $conn->query($sql_insert);
        }
        
        // Refresh to show updated ratings
        header("Location: viewrecipe.php?recipe_id=$recipe_id");
        exit();
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

        .taskbar-left h1{
        color: white;
        }

        .taskbar-left a,
        .taskbar-right a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .taskbar-left a:hover,
        .taskbar-right a:hover {
            background: #25c167;
        }

        .content {
            margin-top: 70px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content button {
            background: #29d978;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .content button:hover {
            background: #25c167;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        h3 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        .back-to-recipes {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-recipes a {
            color: #29d978;
            text-decoration: none;
            border: 1px solid #29d978;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }

        .back-to-recipes a:hover {
            background: #29d978;
            color: white;
        }

        .ratings {
            margin-top: 20px;
        }

        .rating {
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .rating .username {
            font-weight: bold;
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

        <!-- Rating and Comment Form -->
        <h3>Rate this Recipe:</h3>
        <form action="" method="post">
            <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe_id); ?>">
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <br>
            <label for="rating_comment">Comment:</label><br>
            <textarea name="rating_comment" id="rating_comment" rows="4" cols="50" placeholder="Leave a comment (optional)"></textarea><br>
            <input type="submit" value="Submit Rating">
        </form>

        <!-- Display Ratings and Comments -->
        <div class="ratings">
            <h3>All Ratings (Average Rating: <?php echo $avg_rating; ?>)</h3>
            <?php foreach ($ratings as $rating) : ?>
                <div class="rating">
                    <p class="username"><?php echo htmlspecialchars($rating['username']); ?></p>
                    <p>Rating: <?php echo htmlspecialchars($rating['rating']); ?></p>
                    <?php if (!empty($rating['rating_comment'])) : ?>
                        <p>Comment: <?php echo htmlspecialchars($rating['rating_comment']); ?></p>
                    <?php endif; ?>
                    <p>Date: <?php echo htmlspecialchars($rating['rating_date']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="back-to-recipes">
            <a href="viewrecipes.php">Back to Recipes</a>
        </div>

    </div>
</body>
</html>

<?php
$conn->close();
?>
