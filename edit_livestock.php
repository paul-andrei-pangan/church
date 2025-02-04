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
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="livestock.php">🐄 Livestock</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Edit Livestock</h2>
    <form method="POST" action="edit_livestock.php?livestock_id=<?php echo $livestock_id; ?>">
        <label for="livestock_name">Livestock Name:</label>
        <input type="text" name="livestock_name" value="<?php echo $livestock['livestock_name']; ?>" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $livestock['quantity']; ?>" required><br><br>

        <label for="birth_date">Birth Date:</label>
        <input type="date" name="birth_date" value="<?php echo $livestock['birth_date']; ?>" required><br><br>

        <button type="submit">Update Livestock</button>
    </form>
</div>

</body>
</html>
