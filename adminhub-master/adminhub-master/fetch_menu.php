<?php
header('Content-Type: application/json');
include 'db.php';  // Your database connection file

$query = "SELECT id, name, price, image FROM menu ORDER BY id DESC";  // Include 'image' if you have it
$result = $conn->query($query);

$menus = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}

echo json_encode($menus);  // Output as JSON
?>