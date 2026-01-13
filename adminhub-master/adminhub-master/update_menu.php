<?php
include 'db.php';

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';

if ($id === '' || $name === '' || $price === '') {
    echo "Missing data";
    exit;
}

$stmt = $conn->prepare("UPDATE menu SET name=?, price=? WHERE id=?");
$stmt->bind_param("sdi", $name, $price, $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo $stmt->error;
}
