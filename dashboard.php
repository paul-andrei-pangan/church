<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Count the total number of crops
$totalCropsQuery = "SELECT COUNT(*) AS total_crops FROM crops WHERE user_id = '$user_id'";
$totalCropsResult = $conn->query($totalCropsQuery);
$totalCrops = $totalCropsResult->fetch_assoc()['total_crops'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dstyle.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Welcome to Your Dashboard</h2>
    <ul>
    <li><a href="index.php">🏠 Home</a></li>
    <li><a href="profile.php">👤 Profile</a></li>
    <li><a href="settings.php">⚙ Settings</a></li>
    <li><a href="crops.php">🌱 Crops</a></li>
    <li><a href="livestock.php">🐄 Livestock</a></li> <!-- Added livestock menu item -->
    <li><a href="inventory.php">📦 Inventory</a></li>
    <li><a href="logout.php" class="logout">🚪 Logout</a></li>
</ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Your Dashboard</h2>
    
    <!-- Total Crops Display in a Small Box -->
    <div class="total-crops-box">
        <h3>Total Crops</h3>
        <span><?php echo $totalCrops; ?></span> <!-- Display the total crops count -->
    </div>

</div>

</body>
</html>
