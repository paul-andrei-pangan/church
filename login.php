<?php
session_start();
include 'db.php'; // Make sure db.php is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query (adjust the column name if necessary)
    $stmt = $conn->prepare("SELECT user_id, FullName, password FROM users WHERE username=?");

    if ($stmt === false) {
        // If statement preparation fails, output the error
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);  // "s" means the parameter is a string
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $fullname, $hashed_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fullname'] = $fullname;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h2>Login</h2>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
