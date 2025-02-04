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

<h2>Pending Users for Approval</h2>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Address</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td>
                <a href="approve_user.php?id=<?php echo $row['user_id']; ?>">Approve</a>
            </td>
        </tr>
    <?php } ?>
</table>
