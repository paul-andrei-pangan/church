<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/dstyle.css">
</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="profile.php">👤 Profile</a></li>
            <li><a href="settings.php">⚙ Settings</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2>
        
    </div>

</body>
</html>
