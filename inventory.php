<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the list of crops the user has in their inventory using a prepared statement
$query = $conn->prepare("SELECT i.inventory_id, c.crop_name, i.quantity, i.planting_date, i.harvest_date 
                         FROM inventory i 
                         INNER JOIN crops c ON i.crop_id = c.crop_id
                         WHERE i.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$inventoryResult = $query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #007bff;
            color: white;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            top: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
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
            padding: 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: #0056b3;
        }

        .logout {
            color: red !important;
        }

        .main-content {
            margin-left: 270px;
            padding: 40px;
            width: 100%;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 28px;
        }

        h3 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: #f1f1f1;
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
                padding: 30px;
            }

            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
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
                    <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['planting_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['harvest_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
