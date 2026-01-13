<?php
include 'db.php';

$result = $conn->query("SELECT * FROM menu");
$menus = [];

while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

header('Content-Type: application/json');
echo json_encode($menus);
