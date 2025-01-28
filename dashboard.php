<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h1>Welcome to the Church Management Dashboard</h1>";
echo "<p>Welcome, " . $_SESSION['user_id'] . "</p>";
?>

<a href="logout.php">Logout</a>
