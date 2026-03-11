<?php
require_once 'db.php';

header('Content-Type: application/json');

$email = $_GET['email'] ?? '';

if (empty($email)) {
    echo json_encode(['error' => 'Email required']);
    exit;
}

$stmt = $conn->prepare("SELECT profilePic FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['profile_picture' => $user['profilePic']]);
} else {
    echo json_encode(['profile_picture' => '']);
}
?>
