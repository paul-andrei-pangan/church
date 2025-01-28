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
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <h2>Church</h2>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_members.php">Manage Members</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="finances.php">Finances</a></li>
                <li><a href="settings.php">Settings</a></li>
                <a href="logout.php" class="logout-btn">Logout</a>
            </ul>
        </nav>

        <!-- Main content area -->
        <div class="main-content">
            <header>
                <div class="user-info">
                    <p>Welcome, <?php echo $_SESSION['user_id']; ?></p>
                </div>
            </header>

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
