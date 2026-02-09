<?php
include "db.php";

$id = $_POST['id'] ?? null;

if (!$id) {
    exit("Invalid ID");
}

$stmt = $conn->prepare("SELECT image FROM menu WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['image'] && file_exists($row['image'])) {
    unlink($row['image']);
}

$stmt = $conn->prepare("DELETE FROM menu WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Menu deleted";
