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
        <button onclick="location.href='viewrecipes.php'">View Recipes</button>
    </div>
</body>
</html>
