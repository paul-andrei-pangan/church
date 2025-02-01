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
    <title>Home</title>
    <link rel="stylesheet" href="css/istyle.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="fade-in">
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['fullname']; ?>!</h2>
        <p>This is your homepage.</p>

            <div class="box-container">
            <div class="box" onclick="navigateTo('dashboard.php')">
            <i class="fa-solid fa-tachometer-alt fa-3x"></i> <!-- Dashboard Icon -->
            <h3>Dashboard</h3>
            <p>Go to your dashboard</p>
            </div>
            <div class="box" onclick="navigateTo('profile.php')">
                <i class="fa-solid fa-user fa-3x"></i>
                <h3>Profile</h3>
                <p>Manage your profile</p>
            </div>
            <div class="box" onclick="navigateTo('settings.php')">
                <i class="fa-solid fa-gears fa-3x"></i>
                <h3>Settings</h3>
                <p>Adjust settings</p>
            </div>
            <div class="box logout" onclick="navigateTo('logout.php')">
                <i class="fa-solid fa-right-from-bracket fa-3x"></i>
                <h3>Logout</h3>
                <p>Log out from your account</p>
            </div>
        </div>
    </div>

    <script>
        function navigateTo(url) {
            document.body.classList.add("fade-out"); // Mag-aapply ng fade-out effect
            setTimeout(() => {
                window.location.href = url; // Lilipat sa ibang page
            }, 500); // Delay ng 0.5s para sa smooth effect
        }
    </script>

</body>
</html>
