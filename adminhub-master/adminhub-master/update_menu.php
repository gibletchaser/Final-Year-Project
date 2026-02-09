<?php
include "db.php";

$id    = $_POST['id'];
$name  = $_POST['name'];
$price = $_POST['price'];

$imageSQL = "";
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

    $imageSQL = ", image=?";
}

if ($imagePath) {
    $stmt = $conn->prepare(
        "UPDATE menu SET name=?, price=? $imageSQL WHERE id=?"
    );
    $stmt->bind_param("sdsi", $name, $price, $imagePath, $id);
} else {
    $stmt = $conn->prepare(
        "UPDATE menu SET name=?, price=? WHERE id=?"
    );
    $stmt->bind_param("sdi", $name, $price, $id);
}

$stmt->execute();
echo "Menu updated";
