<?php
require_once 'db.php';

header('Content-Type: application/json');

$email = $_GET['email'] ?? '';

if (empty($email)) {
    echo json_encode(['error' => 'Email required']);
    exit;
}

$email = mysqli_real_escape_string($conn, $email);
$result = mysqli_query($conn, "SELECT profilePic FROM customer WHERE email = '$email' LIMIT 1");
$user = $result ? mysqli_fetch_assoc($result) : null;

if ($user) {
    echo json_encode(['profile_picture' => $user['profilePic']]);
} else {
    echo json_encode(['profile_picture' => '']);
}
?>