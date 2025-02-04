<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the list of crops the user has in their inventory
$query = "SELECT i.inventory_id, c.crop_name, i.quantity, i.planting_date, i.harvest_date 
          FROM inventory i 
          INNER JOIN crops c ON i.crop_id = c.crop_id
          WHERE i.user_id = '$user_id'";
$inventoryResult = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="css/dstyle.css">
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
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
    <h2>Your Inventory</h2>
    
    <h3>Crops in Your Inventory:</h3>
    <table>
        <thead>
            <tr>
                <th>Crop Name</th>
                <th>Quantity</th>
                <th>Planting Date</th>
                <th>Harvest Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $inventoryResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['crop_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['planting_date']; ?></td>
                    <td><?php echo $row['harvest_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
