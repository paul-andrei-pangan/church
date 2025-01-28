<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h1>Welcome to the Church Management Dashboard</h1>";
echo "<p>Welcome, " . $_SESSION['user_id'] . "</p>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<a href="logout.php">Logout</a>
