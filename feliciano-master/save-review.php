<?php
session_start();
include 'db.php';

header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "error: Invalid request"; exit();
}

$name    = trim($_POST['name'] ?? 'Anonymous Guest');
$comment = trim($_POST['comment'] ?? '');
$rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;

$email = null;
if (isset($_SESSION['email']) && filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_SESSION['email'];
}

// Keep the database insert exactly as it was originally
$delete_code = null;

$stmt = $conn->prepare("
    INSERT INTO reviews 
    (reviewer_name, reviewer_email, rating, comment, delete_code, created_at) 
    VALUES (?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssiss", $name, $email, $rating, $comment, $delete_code);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $stmt->error;
}

$stmt->close();
?>