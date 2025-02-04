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

// SQL to fetch the user's data (removed 'ministry')
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
    // Do not display the password in the profile for security reasons
} else {
    echo "User not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/fstyle.css">
    <!-- Font Awesome for Icons -->
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
        margin-left: 280px; /* Allow space for the sidebar */
        padding: 20px;
        max-width: 100%;
        overflow: auto;
        background-color: #f8f9fa;
    }

    h2 {
        color: #333;
        font-size: 22px;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Profile Section */
    .profile-section {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        width: 100%;
        max-width: 400px; /* Limit width for mobile screens */
        margin-bottom: 30px;
    }

    .profile-section p {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .profile-section strong {
        color: #007bff; /* Blue color for the labels */
    }

    /* Buttons */
    .btn-edit, .btn-back {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .btn-edit:hover, .btn-back:hover {
        background-color: #0056b3;
    }

    .btn-back {
        background-color: #e74c3c;
        width: 20px;
    }

    .btn-back:hover {
        background-color: #c0392b;
    }

    /* Mobile responsive styles */
    @media (max-width: 768px) {
        .container {
            margin-left: 0; /* Remove left margin on mobile */
            padding: 10px;
        }

        .sidebar {
            width: 200px; /* Narrow the sidebar on smaller screens */
            padding: 10px;
        }

        .sidebar h2 {
            font-size: 16px;
        }

        .sidebar ul li a {
            font-size: 12px;
            padding: 6px 10px;
        }

        .profile-section {
            width: 90%; /* Make the profile form width smaller for mobile */
        }

        .btn-edit, .btn-back {
            font-size: 14px; /* Smaller button text for mobile */
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
        <h2><i class="fa-solid fa-user"></i> User Profile</h2>
        <div class="profile-section">
            <p><strong>Full Name:</strong> <?php echo $fullname; ?></p>
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Contact:</strong> <?php echo $contact; ?></p>
            <!-- Removed ministry field -->
        </div>
        <a href="edit_profile.php" class="btn-edit">Edit Profile</a>
    </div>

    <script>
        function goBack() {
            document.body.classList.add("fade-out");
            setTimeout(() => {
                window.location.href = "dashboard.php"; // Redirect to dashboard
            }, 500);
        }

        // Slide-down effect when entering the profile page
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".profile-section").classList.add("slide-down");
        });
    </script>

</body>
</html>
