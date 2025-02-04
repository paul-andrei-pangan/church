<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, FullName, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fullname, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
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
    <!-- Add Google Fonts link -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
/* General body styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
    position: relative;
    height: 100vh;
}

/* Styling for the background video */
.background-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1; /* Make sure the video stays behind the content */
}

#bg-video {
    object-fit: cover; /* Ensures the video covers the entire background area */
    width: 100%;
    height: 100%;
}

/* Container for the login form */
.container {
    background-color: rgba(255, 255, 255, 0.8); /* Slight transparency for the form */
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    position: relative;
    z-index: 1; /* Ensure the form is above the video */
}

/* Title of the site with farm icon */
.site-title {
    font-family: 'Sacramento', cursive;
    font-size: 48px;
    color: #3f51b5;
    margin-bottom: 20px;
    letter-spacing: 2px;
}

/* Adjust the icon size and margin */
.site-title i {
    font-size: 50px;
    margin-right: 10px;
}

/* Login heading */
h2 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

/* Styling for input fields */
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
}

/* Login button styling */
button {
    width: 100%;
    padding: 10px;
    background-color: #3f51b5;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    cursor: pointer;
}

button:hover {
    background-color: #283593;
}

/* Error message styling */
.error {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
    font-family: 'Poppins', sans-serif;
}

/* Paragraph text styles */
p {
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
}

/* Styling for links */
a {
    color: #3f51b5;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

</style>
<body>  

<!-- Background Video -->
<div class="background-video">
    <video autoplay muted loop id="bg-video">
        <!-- Path to the video file in the 'video' folder (change the video to farm-related one) -->
        <source src="video/bgchurch.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

    <!-- Login Form Container -->
    <div class="container">
        <!-- Title for Farm Management System with farm icon -->
        <h1 class="site-title">
            <i class="fas fa-tractor"></i> Farm Management Systemmmmmmm
        </h1>

        <h2>Admin Login</h2>

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
