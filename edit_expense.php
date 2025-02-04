<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['expense_id'])) {
    $expense_id = $_GET['expense_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the existing expense data using prepared statements
    $query = $conn->prepare("SELECT * FROM expenses WHERE expense_id = ? AND user_id = ?");
    $query->bind_param("ii", $expense_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 0) {
        echo "Expense not found or not yours.";
        exit();
    }

    $expense = $result->fetch_assoc();
} else {
    echo "No expense selected.";
    exit();
}

// Update the expense if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense_name = $_POST['expense_name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $expense_date = $_POST['expense_date'];

    // Validate the form fields
    if (empty($expense_name) || empty($amount) || empty($expense_date)) {
        echo "Please fill out all required fields.";
        exit();
    }

    // Update the expense using prepared statements
    $updateQuery = $conn->prepare("UPDATE expenses SET expense_name = ?, amount = ?, description = ?, expense_date = ? WHERE expense_id = ? AND user_id = ?");
    $updateQuery->bind_param("sdssii", $expense_name, $amount, $description, $expense_date, $expense_id, $user_id);

    if ($updateQuery->execute()) {
        echo "Expense updated successfully!";
        header("Location: expenses.php"); // Redirect back to expenses page
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
    <title>Edit Expense</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            flex-grow: 1;
        }

        h2 {
            color: #333;
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
    </style>
</head>
<body>

<div class="sidebar">
    <h2>💸 Dashboard</h2>
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
    </ul>a href="logout.php" class="logout">🚪 Logout</a></li>
    
</div>

<div class="main-content">
    <h2>Edit Expense</h2>

    <div class="form-container">
        <h3>Update Your Expense</h3>
        <form method="POST" action="edit_expense.php?expense_id=<?php echo $expense_id; ?>">
            <label for="expense_name">Expense Name:</label>
            <input type="text" name="expense_name" value="<?php echo htmlspecialchars($expense['expense_name']); ?>" required>

            <label for="amount">Amount:</label>
            <input type="number" name="amount" value="<?php echo htmlspecialchars($expense['amount']); ?>" step="0.01" required>

            <label for="description">Description:</label>
            <textarea name="description"><?php echo htmlspecialchars($expense['description']); ?></textarea>

            <label for="expense_date">Expense Date:</label>
            <input type="date" name="expense_date" value="<?php echo htmlspecialchars($expense['expense_date']); ?>" required>

            <button type="submit">Update Expense</button>
        </form>
    </div>
</div>

</body>
</html>
