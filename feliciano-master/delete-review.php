<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: about.php?error=invalid");
    exit();
}

$review_id  = (int)$_GET['id'];
$user_email = $_SESSION['email'];

$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND reviewer_email = ?");
$stmt->bind_param("is", $review_id, $user_email);

$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: about.php?status=deleted");
} else {
    header("Location: about.php?error=cannot_delete");
}

$stmt->close();
?>