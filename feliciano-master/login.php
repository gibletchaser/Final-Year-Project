<?php
session_start();
require "db.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status"  => "error",
        "message" => "Only POST requests are allowed"
    ]);
    exit();
}

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

if (empty($email) || empty($pass)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Email and password are required"
    ]);
    exit();
}

$stmt = $conn->prepare("SELECT name, email, phone, password FROM user WHERE email = ?");
if (!$stmt) {
    echo json_encode([
        "status"  => "error",
        "message" => "Database prepare error: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Plain text password check (temporary - upgrade to hashing soon!)
    if ($pass === $user['password']) {
        // Success - do NOT send password back to frontend
        $safeUser = [
            "name"  => $user['name'],
            "email" => $user['email'],
            "phone" => $user['phone']
        ];

        // Optional: Store in session if you want server-side session too
        $_SESSION['user'] = $safeUser;

        echo json_encode([
            "status" => "success",
            "user"   => $safeUser
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Invalid Email or Password"
        ]);
    }
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Invalid Email or Password"
    ]);
}

$stmt->close();
exit();
?>