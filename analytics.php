<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsersRow = $totalUsersResult->fetch_assoc();
$totalUsers = $totalUsersRow['total_users'];

// Fetch total recipes
$totalRecipesQuery = "SELECT COUNT(*) as total_recipes FROM recipe";
$totalRecipesResult = $conn->query($totalRecipesQuery);
$totalRecipesRow = $totalRecipesResult->fetch_assoc();
$totalRecipes = $totalRecipesRow['total_recipes'];

// Fetch currently logged-in users (assuming you have a session table or similar mechanism)
$loggedInUsers = 0;
if (isset($_SESSION['logged_in_users'])) {
    $loggedInUsers = count($_SESSION['logged_in_users']);
}

// Fetch highest rated recipe
$highestRatedRecipeQuery = "
    SELECT r.recipe_id, r.recipe, AVG(rt.rating) as avg_rating
    FROM recipe r
    JOIN rating rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating DESC
    LIMIT 1";
$highestRatedRecipeResult = $conn->query($highestRatedRecipeQuery);
$highestRatedRecipe = $highestRatedRecipeResult->fetch_assoc();

// Fetch lowest rated recipe
$lowestRatedRecipeQuery = "
    SELECT r.recipe_id, r.recipe, AVG(rt.rating) as avg_rating
    FROM recipe r
    JOIN rating rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating ASC
    LIMIT 1";
$lowestRatedRecipeResult = $conn->query($lowestRatedRecipeQuery);
$lowestRatedRecipe = $lowestRatedRecipeResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f4f4f4;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #29d978;
            text-align: center;
            margin-bottom: 20px;
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

        .taskbar-left h1 {
            color: white;
            margin-right: 20px;
        }

        .taskbar-left,
        .taskbar-right {
            display: flex;
            align-items: center;
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

        .stats {
            text-align: center;
            margin-top: 20px;
        }

        .stats div {
            margin: 10px 0;
            font-size: 18px;
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
        <button onclick="location.href='admin.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
    <div class="container">
        <h2>Analytics</h2>
        <div class="stats">
            <div>Total Users: <?php echo $totalUsers; ?></div>
            <div>Total Recipes: <?php echo $totalRecipes; ?></div>
            <div>Currently Logged-in Users: <?php echo $loggedInUsers; ?></div>
            <div>Highest Rated Recipe: <?php echo $highestRatedRecipe['recipe']; ?> (Rating: <?php echo round($highestRatedRecipe['avg_rating'], 2); ?>)</div>
            <div>Lowest Rated Recipe: <?php echo $lowestRatedRecipe['recipe']; ?> (Rating: <?php echo round($lowestRatedRecipe['avg_rating'], 2); ?>)</div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>