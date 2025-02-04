<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT * FROM users WHERE status='pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Optional: Style for the Menu */
        .menu {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
        }

        .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .menu a:hover {
            background-color: #444;
        }

        .menu-btn {
            font-size: 30px;
            cursor: pointer;
            color: black;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu Button -->
    <div class="d-flex justify-content-end mt-3">
        <span class="menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </span>
    </div>

    <!-- Menu -->
    <div id="menu" class="menu">
        <a href="logout.php">Sign Out</a>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Pending Users for Approval</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                                <a href="approve_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to toggle the menu visibility
        function toggleMenu() {
            const menu = document.getElementById("menu");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
