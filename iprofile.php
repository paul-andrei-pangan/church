<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/fstyle.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="fade-in">

    <div class="container">
        <h2><i class="fa-solid fa-user"></i> User Profile</h2>
        <div class="profile-section">
            <p><strong>Full Name:</strong> <?php echo $_SESSION['fullname']; ?></p>
            <p><strong>Username:</strong> <?php echo $_SESSION['user_id']; ?></p>
            <!-- Removed Email section -->
        </div>
        <button onclick="goBack()" class="btn-back">Back to Home</button>
    </div>

    <script>
        function goBack() {
            document.body.classList.add("fade-out");
            setTimeout(() => {
                window.location.href = "index.php";
            }, 500);
        }

        // Slide-down effect when entering the profile page
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".profile-section").classList.add("slide-down");
        });
    </script>

</body>
</html>
