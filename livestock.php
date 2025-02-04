<?php
session_start();
include('db.php'); // Include your database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get livestock data from the form
    $livestock_name = $_POST['livestock_name'];
    $livestock_quantity = $_POST['livestock_quantity'];
    $birth_date = $_POST['birth_date'];
    $harvest_date = $_POST['harvest_date'];

    // Step 1: Insert data into the inventory table
    $insertInventoryQuery = "INSERT INTO inventory (user_id) VALUES ('$user_id')";
    if ($conn->query($insertInventoryQuery) === TRUE) {
        // Get the last inserted inventory_id
        $inventory_id = $conn->insert_id;

        // Step 2: Insert livestock data using the inventory_id
        $insertLivestockQuery = "INSERT INTO livestock (inventory_id, livestock_name, livestock_quantity, birth_date, harvest_date) 
                                 VALUES ('$inventory_id', '$livestock_name', '$livestock_quantity', '$birth_date', '$harvest_date')";

        if ($conn->query($insertLivestockQuery) === TRUE) {
            $success_message = "Livestock added successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "Error inserting into inventory: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Livestock</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Add your existing styles here */
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🌿 Dashboard</h2>
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
    <h2>🐄 Add Livestock</h2>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h3>Enter Livestock Details</h3>
        <form method="POST" action="livestock.php">
            <label for="livestock_name">Livestock Name:</label>
            <input type="text" name="livestock_name" id="livestock_name" required placeholder="Enter livestock name">

            <label for="livestock_quantity">Quantity:</label>
            <input type="number" name="livestock_quantity" id="livestock_quantity" required placeholder="Enter quantity">

            <label for="birth_date">Birth Date:</label>
            <input type="date" name="birth_date" id="birth_date" required>

            <label for="harvest_date">Harvest Date:</label>
            <input type="date" name="harvest_date" id="harvest_date" required>

            <button type="submit">🐄 Add Livestock</button>
        </form>
    </div>
</div>

</body>
</html>
