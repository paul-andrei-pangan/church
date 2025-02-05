<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include('db.php');

// Retrieve the user's data from the database
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

// SQL to fetch the user's data
$sql = "SELECT fullname, address, contact, username, password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id); // 's' means string (user_id is a string)
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists and fetch data
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
    $fullname = $user['fullname'];
    $address = $user['address'];
    $contact = $user['contact'];
    $username = $user['username'];
    $hashed_password = $user['password']; // Store the hashed password
} else {
    echo "User not found!";
    exit();
}

// Handle the form submission for updating the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = $_POST['fullname'];
    $new_address = $_POST['address'];
    $new_contact = $_POST['contact'];
    $new_password = $_POST['password'];

    // If password is updated, hash it
    if (!empty($new_password)) {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
    } else {
        $new_password = $hashed_password; // Keep the existing password if not updated
    }

    // SQL query to update user data (removed 'ministry' column)
    $update_sql = "UPDATE users SET fullname=?, address=?, contact=?, password=? WHERE user_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssss", $new_fullname, $new_address, $new_contact, $new_password, $user_id);

    if ($stmt->execute()) {
        // If update is successful, redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
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
        font-family: 'Poppins', sans-serif;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 18px;
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
        font-size: 14px;
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

    /* Main content container */
    .container {
        margin-left: 280px;
        padding: 20px;
        background-color: #f8f9fa;
    }

    h2 {
        color: white;
        font-size: 22px;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Form Styling */
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px; /* Limit width for the form */
        margin: 0 auto;
    }

    form label {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    form input {
        width: 100%;
        padding: 8px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    form button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0056b3;
    }

    /* Back button styling */
    .btn-back {
        display: inline-block;
        background-color: #e74c3c;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        margin-top: 20px;
    }

    .btn-back:hover {
        background-color: #c0392b;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .container {
            margin-left: 0; /* Remove sidebar space on smaller screens */
            padding: 10px;
        }

        .sidebar {
            width: 200px;
            padding: 10px;
        }

        .sidebar h2 {
            font-size: 16px;
        }

        form {
            padding: 15px;
            width: 90%;
        }

        form input, form button {
            font-size: 14px;
        }

        .btn-back {
            width: 100%;
        }
    }
</style>

   
</head>
<body class="fade-in">
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

    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" value="<?php echo $fullname; ?>" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo $address; ?>" required><br>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" value="<?php echo $contact; ?>" required><br>

            <!-- Removed ministry field -->
            <label for="password">New Password (Leave blank to keep current):</label>
            <input type="password" name="password" placeholder="Enter new password"><br>

            <button type="submit">Save Changes</button>
        </form>
    </div>

</body>
</html>
