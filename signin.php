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
    <title>Signin</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <form action="signin.php" method="post">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" required><br>

        <label for="address">Address</label>
        <input type="text" name="address" required><br>

        <label for="contact">Contact</label>
        <input type="text" name="contact" required><br>

        <label for="ministry">Ministry</label>
        <input type="text" name="ministry" required><br>

        <label for="username">Username</label>
        <input type="text" name="username" required><br>

        <label for="password">Password</label>
        <input type="password" name="password" required><br>

        <button type="submit">Create Account</button>
    </form>
</body>
</html>
