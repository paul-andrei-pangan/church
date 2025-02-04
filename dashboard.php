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

// Count the total number of livestock
$totalLivestockQuery = "SELECT COUNT(*) AS total_livestock FROM livestock WHERE user_id = '$user_id'";
$totalLivestockResult = $conn->query($totalLivestockQuery);
$totalLivestock = $totalLivestockResult->fetch_assoc()['total_livestock'];

// Count the total number of inventory items
$totalInventoryQuery = "SELECT COUNT(*) AS total_inventory FROM inventory WHERE user_id = '$user_id'";
$totalInventoryResult = $conn->query($totalInventoryQuery);
$totalInventory = $totalInventoryResult->fetch_assoc()['total_inventory'];

// Count the total expenses
$totalExpensesQuery = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = '$user_id'";
$totalExpensesResult = $conn->query($totalExpensesQuery);
$totalExpenses = $totalExpensesResult->fetch_assoc()['total_expenses'];
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
        <li><a href="livestock.php">🐄 Livestock</a></li> 
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="expenses.php">💸 Expenses</a></li>
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

    <!-- Total Livestock Display in a Small Box -->
    <div class="total-livestock-box">
        <h3>Total Livestock</h3>
        <span><?php echo $totalLivestock; ?></span> <!-- Display the total livestock count -->
    </div>

    <!-- Total Inventory Display in a Small Box -->
    <div class="total-inventory-box">
        <h3>Total Inventory</h3>
        <span><?php echo $totalInventory; ?></span> <!-- Display the total inventory count -->
    </div>

    <!-- Total Expenses Display in a Small Box -->
    <div class="total-expenses-box">
        <h3>Total Expenses</h3>
        <span>₱<?php echo number_format($totalExpenses, 2); ?></span> <!-- Display the total expenses -->
    </div>

</div>

</body>
</html>
