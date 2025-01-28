<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
        }
        main {
            padding: 20px;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container a {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            font-size: 16px;
        }
        .button-container a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Church Management System</h1>
    </header>

    <main>
        <p>Your one-stop solution to manage church activities and members.</p>
        
        <div class="button-container">
            <a href="login.php">Login</a>
            <a href="signin.php">Sign Up</a>
        </div>
    </main>
</body>
</html>
