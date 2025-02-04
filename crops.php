<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle crop addition with planting date and harvest date
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crop_name = $_POST['crop_name'];
    $planting_date = $_POST['planting_date'];
    $harvest_date = $_POST['harvest_date'];

    // Insert crop into the crops table
    $insertCropQuery = "INSERT INTO crops (user_id, crop_name, planting_date, harvest_date) 
                        VALUES ('$user_id', '$crop_name', '$planting_date', '$harvest_date')";

    if ($conn->query($insertCropQuery) === TRUE) {
        $crop_id = $conn->insert_id; // Get the last inserted crop_id

        // Insert into inventory with quantity 0 initially
        $insertInventoryQuery = "INSERT INTO inventory (user_id, crop_id, quantity, planting_date, harvest_date) 
                                 VALUES ('$user_id', '$crop_id', 0, '$planting_date', '$harvest_date')";

        if ($conn->query($insertInventoryQuery) === TRUE) {
            echo "Crop added to both crops and inventory successfully!";
        } else {
            echo "Error adding crop to inventory: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Crop</title>
    <link rel="stylesheet" href="css/dstyle.css">
</head>
<body>

<div class="sidebar">
    <h2>Farm Dashboard</h2>
    <ul>
        <li><a href="index.php">🏠 Home</a></li>
        <li><a href="profile.php">👤 Profile</a></li>
        <li><a href="settings.php">⚙ Settings</a></li>
        <li><a href="crops.php">🌱 Crops</a></li>
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>Add a New Crop</h2>
    <p>Plant your seeds and grow your farm! 🌾</p>
    
    <form method="POST" action="crops.php">
        <label for="crop_name">Crop Name:</label>
        <input type="text" name="crop_name" id="crop_name" required placeholder="Enter crop name"><br><br>

        <label for="planting_date">Planting Date:</label>
        <input type="date" name="planting_date" id="planting_date" required><br><br>

        <label for="harvest_date">Harvest Date:</label>
        <input type="date" name="harvest_date" id="harvest_date" required><br><br>

        <button type="submit">Add Crop</button>
    </form>

</div>

</body>
</html>
