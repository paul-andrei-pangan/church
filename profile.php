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
$sql = "SELECT fullname, address, contact, ministry, username, password FROM users WHERE user_id = ?";
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
    $ministry = $user['ministry'];
    $username = $user['username'];
    // Do not display the password in the profile for security reasons
    $password = $user['password']; // Not used in the profile
} else {
    // If no user found, display an error or redirect
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
</head>
<body class="fade-in">

    <div class="container">
        <h2><i class="fa-solid fa-user"></i> User Profile</h2>
        <div class="profile-section">
            <p><strong>Full Name:</strong> <?php echo $fullname; ?></p>
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Contact:</strong> <?php echo $contact; ?></p>
            <p><strong>Ministry:</strong> <?php echo $ministry; ?></p>
            <!-- Don't display password for security reasons -->
        </div>
        <button onclick="goBack()" class="btn-back">Back to Home</button>
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
