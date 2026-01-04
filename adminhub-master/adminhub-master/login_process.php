<?php
session_start();
include '../db.php';

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === 'staff01' && $password === '123') {

    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = $username;

    header("Location: index.php");
    exit;
} else {
    header("Location: login.html");
    exit;
}
