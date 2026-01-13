<?php
include 'db.php';

$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';

if ($name === '' || $price === '') {
    echo "Missing data";
    exit;
}

$stmt = $conn->prepare("INSERT INTO menu (name, price) VALUES (?, ?)");
$stmt->bind_param("sd", $name, $price);

if ($stmt->execute()) {
    echo "success";
} else {
    echo $stmt->error;
}
