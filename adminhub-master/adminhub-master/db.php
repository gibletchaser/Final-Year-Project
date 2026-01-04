<?php

$conn = new mysqli("localhost", "root", "", "yob_yong");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
