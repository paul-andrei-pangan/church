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
    <title>Sign Up - Church Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
       
        <form action="signup_process.php" method="POST">
            <h2>Create an Account</h2>
            
            <!-- Form fields -->
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="text" name="ministry" placeholder="Ministry" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Create your password" required>
            
            <!-- Submit Button -->
            <button type="submit">Sign Up</button>

            <!-- Footer with login link -->
            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </form>
    </div>
</body>
</html>
