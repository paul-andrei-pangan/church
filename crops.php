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
            background: #007bff;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background: #0056b3;
        }

        .logout {
            color: red !important;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            flex-grow: 1;
        }

        h2 {
            color: #333;
        }

        /* Add Crop Form */
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: auto;
        }

        .form-container h3 {
            color: #007bff;
            font-size: 22px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        /* Success/Error Messages */
        .message {
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .success {
            background: #28a745;
            color: white;
        }

        .error {
            background: #dc3545;
            color: white;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .form-container {
                width: 100%;
                max-width: 400px;
                margin: auto;
            }
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

            <button type="submit">🌾 Add Crop</button>
        </form>
    </div>
</div>

</body>
</html>