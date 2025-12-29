<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
}
?>

<h2>Admin Dashboard</h2>
<a href="../profile.php">View Profile</a>
