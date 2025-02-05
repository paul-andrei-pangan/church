<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crop_name = $_POST['crop_name'];
    $planting_date = $_POST['planting_date'];
    $harvest_date = $_POST['harvest_date'];

    $insertCropQuery = "INSERT INTO crops (user_id, crop_name, planting_date, harvest_date) 
                        VALUES ('$user_id', '$crop_name', '$planting_date', '$harvest_date')";

    if ($conn->query($insertCropQuery) === TRUE) {
        $crop_id = $conn->insert_id;
        $insertInventoryQuery = "INSERT INTO inventory (user_id, crop_id, quantity, planting_date, harvest_date) 
                                 VALUES ('$user_id', '$crop_id', 0, '$planting_date', '$harvest_date')";

        if ($conn->query($insertInventoryQuery) === TRUE) {
            $success_message = "Crop added successfully!";
        } else {
            $error_message = "Error adding crop to inventory: " . $conn->error;
        }
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Crop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
       /* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background: #f8f9fa;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
        width: 250px;
        background-color: #2c3e50;
        color: white;
        padding: 20px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 18px; /* Smaller font for consistency */
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin: 8px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        font-size: 14px; /* Smaller font size for compactness */
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .sidebar ul li a:hover,
    .sidebar ul li a.active {
        background: #34495e;
    }

/* Main Content */
.main-content {
    margin-left: 280px;
    padding: 20px;
    flex-grow: 1;
}

    h2 {
        color: white;
        font-size: 22px;
        text-align: center; /* Center the title */
        margin-bottom: 30px;
    }

    /* Table Styles */
    table {
        width: 80%; /* Limit table width for better centering */
        border-collapse: collapse;
        margin-top: 20px;
        border: 1px solid #ddd;
        margin-left: auto; /* Center the table */
        margin-right: auto;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        font-size: 14px;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    /* Form Styles */
    .form-container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        width: 100%;
        max-width: 500px; /* Max width for the form */
        margin-left: auto;
        margin-right: auto;
    }

    .form-container h3 {
        margin-bottom: 20px;
        font-size: 18px;
        color: #333;
        text-align: center; /* Center the form title */
    }

    label {
        font-size: 14px;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    input[type="text"], input[type="date"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    input[type="text"]:focus, input[type="date"]:focus {
        border-color: #007bff;
        outline: none;
    }
    /* Button Styles */
    button {
        background-color: #007bff; /* Blue for Add Crop button */
        color: white;
        padding: 8px 12px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        display: inline-block;
        margin-top: 15px;
        width: 100%; /* Make buttons full-width */
    }

    button:hover {
        background-color: #0056b3; /* Darker blue for hover */
    }

    .cancel-button {
        background-color: #e74c3c; /* Red for Cancel button */
        color: white;
        padding: 8px 12px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        display: inline-block;
        margin-top: 10px;
        width: 100%;
    }

    .cancel-button:hover {
        background-color: #c0392b; /* Darker red for hover */
    }

    /* Message Styles */
    .message {
        margin: 15px 0;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
    }

    .message.success {
        background-color: #2ecc71;
        color: white;
    }

    .message.error {
        background-color: #e74c3c;
        color: white;
    }

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
        <li><a href="livestock.php">🐄 Livestock</a></li>
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="expenses.php">💸 Expenses</a></li>
        <li><a href="income.php">💰 Income</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>🌱 Add a New Crop</h2>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h3>Plant Your Crop</h3>
        <form method="POST" action="crops.php">
            <label for="crop_name">Crop Name:</label>
            <input type="text" name="crop_name" id="crop_name" required placeholder="Enter crop name">

            <label for="planting_date">Planting Date:</label>
            <input type="date" name="planting_date" id="planting_date" required>

            <label for="harvest_date">Harvest Date:</label>
            <input type="date" name="harvest_date" id="harvest_date" required>

            <button type="submit"> Add Crop</button>
            <!-- Cancel Button -->
            <a href="crops.php">
                <button type="button" class="cancel-button">Cancel</button>
            </a>
        </form>
    </div>
</div>

</body>
</html>
