<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = hash('sha256', $_POST['password']);

$sql = "SELECT user_id, role FROM users WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: staff/dashboard.php");
    }
} else {
    echo "Invalid username or password";
}
?>
