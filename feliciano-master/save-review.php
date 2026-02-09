<?php
session_start();
include 'db.php';

header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "error: Invalid request"; exit();
}

// ... honeypot, trim, validation ... (keep your existing code)

$name    = trim($_POST['name'] ?? 'Anonymous Guest');
$comment = trim($_POST['comment'] ?? '');
$rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;

// Generate random delete code (only if comment is not empty)
$delete_code = null;
if (!empty($comment)) {
    $delete_code = bin2hex(random_bytes(5));   // e.g. "a1b2c3d4e5" (10 chars)
}

$email = null;
if (isset($_SESSION['email']) && filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_SESSION['email'];
}

$stmt = $conn->prepare("
    INSERT INTO reviews 
    (reviewer_name, reviewer_email, rating, comment, delete_code, created_at) 
    VALUES (?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssiss", $name, $email, $rating, $comment, $delete_code);

if ($stmt->execute()) {
    if ($delete_code) {
        echo "success|{$delete_code}";
    } else {
        echo "success";
    }
} else {
    echo "error: " . $stmt->error;
}

$stmt->close();
?>