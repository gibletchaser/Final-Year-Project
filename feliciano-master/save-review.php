<?php
session_start();
include 'db.php';

header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "error: Invalid request";
    exit();
}

// Honeypot anti-spam
if (!empty($_POST['website'])) {
    echo "error: Spam detected";
    exit();
}

$name    = trim($_POST['name'] ?? 'Anonymous Guest');
$comment = trim($_POST['comment'] ?? '');
$rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;

if (empty($comment)) {
    echo "error: Please write a review message";
    exit();
}

if ($rating < 1 || $rating > 5) $rating = 5;

// Get email if logged in – otherwise NULL
$email = null;
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    $email = $_SESSION['email'];   // ← this line was probably missing or commented out
}

$stmt = $conn->prepare("
    INSERT INTO reviews 
    (reviewer_name, reviewer_email, rating, comment, created_at) 
    VALUES (?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssis", $name, $email, $rating, $comment);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $stmt->error;
}

$stmt->close();
?>