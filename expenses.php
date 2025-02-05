<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the list of expenses the user has added using prepared statements
$query = $conn->prepare("SELECT expense_id, expense_name, amount, description, expense_date 
                         FROM expenses 
                         WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$expensesResult = $query->get_result();

// Delete expense functionality
if (isset($_GET['delete_expense_id'])) {
    $expense_id = $_GET['delete_expense_id'];

    // Use prepared statement for deletion to prevent SQL injection
    $deleteQuery = $conn->prepare("DELETE FROM expenses WHERE expense_id = ? AND user_id = ?");
    $deleteQuery->bind_param("ii", $expense_id, $user_id);

    if ($deleteQuery->execute()) {
        echo "Expense deleted successfully!";
        header("Location: expenses.php"); // Refresh page after deletion
        exit();
    } else {
        echo "Error deleting expense: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Expenses</title>
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
    padding: 10px;
    text-align: left;
}

th {
    background: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

/* Button Styles */
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

a {
    text-decoration: none;
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

<div class="sidebar">
    <h2>💸Dashboard</h2>
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
    <h3>Your Expenses</h3>

    <!-- Add Expense Button -->
    <a href="add_expenses.php"><button>Add New Expense</button></a><br><br>

    <table>
        <thead>
            <tr>
                <th>Expense Name</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Expense Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $expensesResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['expense_name']); ?></td>
                    <td><?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['expense_date']; ?></td>
                    <td>
                        <a href="edit_expense.php?expense_id=<?php echo $row['expense_id']; ?>">Edit</a> |
                        <a href="expenses.php?delete_expense_id=<?php echo $row['expense_id']; ?>" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
