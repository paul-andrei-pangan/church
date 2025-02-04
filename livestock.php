<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the list of livestock the user has added using prepared statements
$query = $conn->prepare("SELECT livestock_id, livestock_name, quantity, birth_date 
                         FROM livestock 
                         WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$livestockResult = $query->get_result();

// Delete livestock functionality
if (isset($_GET['delete_livestock_id'])) {
    $livestock_id = $_GET['delete_livestock_id'];

    // Use prepared statement for deletion to prevent SQL injection
    $deleteQuery = $conn->prepare("DELETE FROM livestock WHERE livestock_id = ? AND user_id = ?");
    $deleteQuery->bind_param("ii", $livestock_id, $user_id);

    if ($deleteQuery->execute()) {
        echo "Livestock deleted successfully!";
        header("Location: livestock.php"); // Refresh page after deletion
        exit();
    } else {
        echo "Error deleting livestock: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Livestock</title>
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
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🐄 Livestock Dashboard</h2>
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
    <h2>Your Livestock</h2>

    <!-- Add Livestock Button -->
    <a href="add_livestock.php"><button>Add New Livestock</button></a><br><br>

    <table>
        <thead>
            <tr>
                <th>Livestock Name</th>
                <th>Quantity</th>
                <th>Birth Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $livestockResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['livestock_name']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['birth_date']; ?></td>
                    <td>
                        <a href="edit_livestock.php?livestock_id=<?php echo $row['livestock_id']; ?>">Edit</a> |
                        <a href="livestock.php?delete_livestock_id=<?php echo $row['livestock_id']; ?>" onclick="return confirm('Are you sure you want to delete this livestock?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
