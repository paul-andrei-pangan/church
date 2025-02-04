<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $updateQuery = "UPDATE users SET status='approved' WHERE user_id='$id'";
    mysqli_query($conn, $updateQuery);
    header("Location: admin_dashboard.php");
}
?>
