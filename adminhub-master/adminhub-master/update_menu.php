<?php
include "db.php";

$id         = $_POST['id'];
$name       = $_POST['name'];
$price      = $_POST['price'];
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

$imagePath = null;

if (!empty($_FILES['image']['name'])) {

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName   = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $uploadDir . $fileName;

    $allowed = ["jpg", "jpeg", "png", "webp"];
    $ext     = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        exit("Invalid image type");
    }

    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    $imagePath = $targetFile;
}

if ($imagePath) {
    $stmt = $conn->prepare(
        "UPDATE menu SET name=?, price=?, category_id=?, image=? WHERE id=?"
    );
    $stmt->bind_param("sdisi", $name, $price, $category_id, $imagePath, $id);
} else {
    $stmt = $conn->prepare(
        "UPDATE menu SET name=?, price=?, category_id=? WHERE id=?"
    );
    $stmt->bind_param("sdii", $name, $price, $category_id, $id);
}

$stmt->execute();
echo "Menu updated";
?>