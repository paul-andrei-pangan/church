<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include('db.php');

// Retrieve the user's data from the database
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

// SQL to fetch the user's data
$sql = "SELECT fullname, address, contact, username, password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id); // 's' means string (user_id is a string)
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists and fetch data
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
    $fullname = $user['fullname'];
    $address = $user['address'];
    $contact = $user['contact'];
    $username = $user['username'];
    $hashed_password = $user['password']; // Store the hashed password
} else {
    echo "User not found!";
    exit();
}

// Handle the form submission for updating the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = $_POST['fullname'];
    $new_address = $_POST['address'];
    $new_contact = $_POST['contact'];
    $new_password = $_POST['password'];

    // If password is updated, hash it
    if (!empty($new_password)) {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
    } else {
        $new_password = $hashed_password; // Keep the existing password if not updated
    }

    // SQL query to update user data (removed 'ministry' column)
    $update_sql = "UPDATE users SET fullname=?, address=?, contact=?, password=? WHERE user_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssss", $new_fullname, $new_address, $new_contact, $new_password, $user_id);

    if ($stmt->execute()) {
        // If update is successful, redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/fstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="fade-in">

    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" value="<?php echo $fullname; ?>" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo $address; ?>" required><br>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" value="<?php echo $contact; ?>" required><br>

            <!-- Removed ministry field -->
            <label for="password">New Password (Leave blank to keep current):</label>
            <input type="password" name="password" placeholder="Enter new password"><br>

            <button type="submit">Save Changes</button>
        </form>
        <a href="profile.php" class="btn-back">Back to Profile</a>
    </div>

</body>
</html>
