<?php

$conn = new mysqli("localhost", "root", "", "yobyong");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
