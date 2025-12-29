<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, full_name, email, role FROM users WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<h2>My Profile</h2>
<p><b>Username:</b> <?= $user['username']; ?></p>
<p><b>Name:</b> <?= $user['full_name']; ?></p>
<p><b>Email:</b> <?= $user['email']; ?></p>
<p><b>Role:</b> <?= ucfirst($user['role']); ?></p>

<a href="logout.php">Logout</a>
