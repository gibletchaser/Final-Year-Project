<?php
include 'db.php';
$id = $_POST['id'] ?? '';
$name = $_POST['name'];
$price = $_POST['price'];

if ($id) {
    // Update
    $stmt = $conn->prepare("UPDATE menu SET name=?, price=? WHERE id=?");
    $stmt->bind_param("sdi", $name, $price, $id);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO menu (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $name, $price);
}
$stmt->execute();
echo "Success";
?>