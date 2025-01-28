<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $ministry = $_POST['ministry'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (full_name, address, contact, ministry, username, password) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $full_name, $address, $contact, $ministry, $username, $password);
    if ($stmt->execute()) {
        echo "Account created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            Church Management System
        </header>
        <form action="login_process.php" method="POST">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Log In</button>
            <div class="form-footer">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </form>
    </div>
</body>
</html>
