<?php
include 'db.php';

$redirect_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';

// Make sure it's one of our allowed pages
$allowed_pages = ['index.php', 'about.php'];
$redirect_page = in_array(basename($redirect_page), $allowed_pages) 
    ? $redirect_page 
    : 'index.php';

if (!isset($_GET['code']) || strlen($_GET['code']) !== 10) {
    header("Location: $redirect_page?error=invalid_code");
    exit();
}

$code = $_GET['code'];

$stmt = $conn->prepare("DELETE FROM reviews WHERE delete_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: $redirect_page?status=deleted");
} else {
    header("Location: $redirect_page?error=code_not_found");
}

$stmt->close();
?>