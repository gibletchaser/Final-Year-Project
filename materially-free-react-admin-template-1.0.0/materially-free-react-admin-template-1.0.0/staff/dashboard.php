<?php
session_start();
if ($_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
}
?>

<h2>Staff Dashboard</h2>
<a href="../profile.php">View Profile</a>
