<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Insert the expense into the database when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $expense_date = $_POST['expense_date'];

    // Insert the expense into the database
    $query = "INSERT INTO expenses (expense_name, amount, description, expense_date, user_id) 
              VALUES ('$expense_name', '$amount', '$description', '$expense_date', '$user_id')";
    if ($conn->query($query) === TRUE) {
        echo "Expense added successfully!";
        header("Location: expenses.php"); // Redirect to the expenses page
        exit();
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
    <title>Add Expense</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
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
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: 100%;
        }

        h2 {
            color: white;
        }

        /* Add Expense Form */
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

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
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
            background-color: #0056b3;
        }

        /* Success/Error Messages */
        .message {
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .success {
            background-color: #28a745;
            color: white;
        }

        .error {
            background-color: #dc3545;
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
        h3{
            color: black;
        font-size: 22px;
        text-align: center; /* Center the title */
        margin-bottom: 30px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
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

<!-- Main Content -->
<div class="main-content">
    <h3>💸 Add a New Expense</h3>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h3>Record Your Expense</h3>
        <form method="POST" action="add_expenses.php">
            <label for="expense_name">Expense Name:</label>
            <input type="text" name="expense_name" id="expense_name" required placeholder="Enter expense name">

            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" required step="0.01" placeholder="Enter amount">

            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Enter description"></textarea>

            <label for="expense_date">Expense Date:</label>
            <input type="date" name="expense_date" id="expense_date" required>

            <button type="submit">Add Expense</button>
            <br><br>
            <a href="expenses.php">
                <button type="button" style="background-color: #dc3545;">Cancel</button>
            </a>
        </form>
    </div>
</div>

</body>
</html>
