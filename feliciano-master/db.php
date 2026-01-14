<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "yibyong";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
