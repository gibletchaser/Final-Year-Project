<?php
session_start();
include 'db.php'; // Ensure this file connects to 'yibyong'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass  = $_POST['password'];

    // If password is not empty, update it too
    if (!empty($pass)) {
        $query = "UPDATE user SET name='$name', phone='$phone', password='$pass' WHERE email='$email'";
    } else {
        $query = "UPDATE user SET name='$name', phone='$phone' WHERE email='$email'";
    }

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
}
?>