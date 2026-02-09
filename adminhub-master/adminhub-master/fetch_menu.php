<?php
include 'db.php';

$result = $conn->query("SELECT id, name, price, image FROM menu");
$menus = [];

while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

header('Content-Type: application/json');
echo json_encode($menus);
