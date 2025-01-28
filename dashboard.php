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
    <title>Church Management Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <h1>Church Management</h1>
            </div>
            <div class="user-info">
                <p>Welcome, <?php echo $_SESSION['user_id']; ?></p>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>
        
        <div class="main-content">
            <aside class="sidebar">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage_members.php">Manage Members</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="finances.php">Finances</a></li>
                    <li><a href="settings.php">Settings</a></li>
                </ul>
            </aside>

            <section class="content">
                <h2>Dashboard Overview</h2>
                <p>Welcome to the Church Management Dashboard. Here you can manage your members, events, finances, and more.</p>
                <div class="stats">
                    <div class="stat-box">
                        <h3>Members</h3>
                        <p>200+</p>
                    </div>
                    <div class="stat-box">
                        <h3>Upcoming Events</h3>
                        <p>5 events</p>
                    </div>
                    <div class="stat-box">
                        <h3>Donations</h3>
                        <p>$5000+</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
