<?php
session_start();
include 'db.php'; // Ensure you have database connection

$error_message = "";

if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
   <style>
    body {
        background-image: url('img/admin.jpg'); 
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
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow p-4" style="width: 350px;">
            <h3 class="text-center mb-3">Admin Login</h3>

            <?php if ($error_message): ?>
                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <button type="submit" name="admin_login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
