<?php
session_start();
include('db.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['livestock_id'])) {
    $livestock_id = $_GET['livestock_id'];

    // Fetch the existing livestock data
    $query = "SELECT * FROM livestock WHERE livestock_id = '$livestock_id' AND user_id = '$user_id'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        echo "Livestock not found or not yours.";
        exit();
    }

    $livestock = $result->fetch_assoc();
} else {
    echo "No livestock selected.";
    exit();
}

// Update livestock if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $livestock_name = $_POST['livestock_name'];
    $quantity = $_POST['quantity'];
    $birth_date = $_POST['birth_date'];

    $updateQuery = "UPDATE livestock SET livestock_name = '$livestock_name', quantity = '$quantity', birth_date = '$birth_date' 
                    WHERE livestock_id = '$livestock_id' AND user_id = '$user_id'";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Livestock updated successfully!";
        header("Location: livestock.php"); // Redirect to livestock page
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
    <title>Edit Livestock</title>
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
            color: white;
        }

        /* Form Container */
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

        /* Cancel Button */
        .cancel-button {
            background: #dc3545;
            margin-top: 10px;
            width: 100%;
        }

        .cancel-button:hover {
            background: #c82333;
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
    </ul>
</div>

<div class="main-content">
    <h2>Edit Livestock</h2>

    <div class="form-container">
        <h3>Update Livestock Information</h3>
        <form method="POST" action="edit_livestock.php?livestock_id=<?php echo $livestock_id; ?>">
            <label for="livestock_name">Livestock Name:</label>
            <input type="text" name="livestock_name" value="<?php echo $livestock['livestock_name']; ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?php echo $livestock['quantity']; ?>" required>

            <label for="birth_date">Birth Date:</label>
            <input type="date" name="birth_date" value="<?php echo $livestock['birth_date']; ?>" required>

            <button type="submit">Update Livestock</button>
        </form>

        <a href="livestock.php">
            
            <button type="button" class="cancel-button">Cancel</button>
        </a>
    </div>
</div>

</body>
</html>
