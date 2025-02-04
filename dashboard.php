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

// Count the total income (replace 'income' with the actual table and column names)
$totalIncomeQuery = "SELECT SUM(amount) AS total_income FROM income WHERE user_id = '$user_id'";
$totalIncomeResult = $conn->query($totalIncomeQuery);
$totalIncome = $totalIncomeResult->fetch_assoc()['total_income'];

// Calculate total income after subtracting expenses
$netIncome = $totalIncome - $totalExpenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px 10px;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ecf0f1;
            font-size: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0; /* Reduced margin between items */
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px; /* Smaller font size for compactness */
            display: block;
            padding: 8px 12px; /* Reduced padding for smaller buttons */
            border-radius: 4px;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: 100%;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-box h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .stat-box span {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }

        .logout {
            color: #e74c3c;
            text-align: center;
        }

        .logout:hover {
            color: #c0392b;
        }

        #chart-container {
    width: 100%;
    height: 400px; /* Increased height for a bigger graph */
    margin: 40px auto;
    display: flex;
    justify-content: center;
}

#dashboardChart {
    max-width: 95%; /* Increased the max-width to make the graph slightly larger */
    height: 300px;  /* Adjusted height for a better proportion */
}
    </style>
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
        <li><a href="income.php">💰 Income</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Your Dashboard</h2>

    <div class="stats">
        <!-- Total Crops -->
        <div class="stat-box">
            <h3>Total Crops</h3>
            <span><?php echo $totalCrops; ?></span>
        </div>

        <!-- Total Livestock -->
        <div class="stat-box">
            <h3>Total Livestock</h3>
            <span><?php echo $totalLivestock; ?></span>
        </div>

        <!-- Total Inventory -->
        <div class="stat-box">
            <h3>Total Inventory</h3>
            <span><?php echo $totalInventory; ?></span>
        </div>

        <!-- Total Expenses -->
        <div class="stat-box">
            <h3>Total Expenses</h3>
            <span>₱<?php echo number_format($totalExpenses, 2); ?></span>
        </div>

        <!-- Net Income -->
        <div class="stat-box">
            <h3>Net Income</h3>
            <span>₱<?php echo number_format($netIncome, 2); ?></span>
        </div>
    </div>

    <!-- Chart Section -->
    <div id="chart-container">
        <canvas id="dashboardChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('dashboardChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Crops', 'Livestock', 'Inventory', 'Expenses', 'Net Income'],
                datasets: [{
                    label: 'Dashboard Stats',
                    data: [<?php echo $totalCrops; ?>, <?php echo $totalLivestock; ?>, <?php echo $totalInventory; ?>, <?php echo $totalExpenses; ?>, <?php echo $netIncome; ?>],
                    backgroundColor: [
                        '#2ecc71', '#3498db', '#f39c12', '#e74c3c', '#8e44ad'
                    ],
                    borderColor: [
                        '#27ae60', '#2980b9', '#f39c12', '#c0392b', '#8e44ad'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>

</body>
</html>
