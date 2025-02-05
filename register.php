<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        $error_message = "Username is already taken. Please choose a different one.";
    } else {
        // Insert user with status 'pending'
        $stmt = $conn->prepare("INSERT INTO users (FullName, Address, Contact, username, password, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssss", $fullname, $address, $contact, $username, $password);

        if ($stmt->execute()) {
            $success_message = "Registration successful! Your account is pending approval. Please wait for admin approval.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
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
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
            body {
        background-image: url('img/farmm.jpg'); 
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(5px); 
        border-radius: 10px;
    }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registration</h2>

        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="contact" placeholder="Contact" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>

        <p>I have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
