<?php
include "db.php";

$name  = $_POST['name'];
$price = $_POST['price'];

$imagePath = null;

if (!empty($_FILES['image']['name'])) {

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $uploadDir . $fileName;

    $allowed = ["jpg", "jpeg", "png", "webp"];
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        exit("Invalid image type");
    }

    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    $imagePath = $targetFile;
}

$stmt = $conn->prepare(
    "INSERT INTO menu (name, price, image) VALUES (?, ?, ?)"
);
$stmt->bind_param("sds", $name, $price, $imagePath);
$stmt->execute();

echo "Menu added successfully";
