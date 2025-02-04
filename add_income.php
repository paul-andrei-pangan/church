<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input data from the form
    $income_name = $_POST['income_name'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];

    // Insert income data into the database using prepared statement
    $insertQuery = $conn->prepare("INSERT INTO income (user_id, income_name, amount, income_date) 
                                   VALUES (?, ?, ?, ?)");
    $insertQuery->bind_param("isss", $user_id, $income_name, $amount, $income_date);

    if ($insertQuery->execute()) {
        echo "Income added successfully!";
        header("Location: income.php"); // Redirect to the income page after successful insertion
        exit();
    } else {
        echo "Error adding income: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Income</title>
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
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            color: #333;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .cancel-button {
            background-color: #dc3545;
        }

        .cancel-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>💸 Dashboard</h2>
    <ul>
    <li><a href="dashboard.php">🏠 Home</a></li>
        <li><a href="profile.php">👤 Profile</a></li>
        <li><a href="crops.php">🌱 Crops</a></li>
        <li><a href="livestock.php">🐄 Livestock</a></li>
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="expenses.php">💸 Expenses</a></li>
        <li><a href="income.php">💰 Income</a></li>
        <li><a href="settings.php">⚙ Settings</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>Add Income</h2>

    <!-- Add Income Form -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="income_name">Income Name</label>
            <input type="text" id="income_name" name="income_name" required placeholder="Enter income name">
        </div>
        <div class="form-group">
            <label for="amount">Amount (₱)</label>
            <input type="number" id="amount" name="amount" required placeholder="Enter amount" step="0.01">
        </div>
        <div class="form-group">
            <label for="income_date">Income Date</label>
            <input type="date" id="income_date" name="income_date" required>
        </div>
        <button type="submit">Add Income</button>
        <a href="income.php"><button type="button" class="cancel-button">Cancel</button></a>
    </form>
</div>

</body>
</html>
