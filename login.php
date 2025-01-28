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
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="login_action.php" method="post">
                <div class="textbox">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                
                <div class="textbox">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
