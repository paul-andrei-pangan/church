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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
 
</head>
<body>
    <form action="login_action.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Password</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
