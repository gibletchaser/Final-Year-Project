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

$stmt = $conn->prepare("SELECT name, email, phone, password FROM customer WHERE email = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Database error"
    ]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($customer = $result->fetch_assoc()) {
    // Modern secure password check
    if (password_verify($pass, $customer['password'])) {
        // Success
        $safeUser = [
            "name"  => $customer['name'],
            "email" => $customer['email'],
            // "phone" => $customer['phone'],   // ← comment out or remove if not needed on every login
        ];

        $_SESSION['customer_email'] = $customer['email'];
        $_SESSION['customer_name']  = $customer['name'];

        echo json_encode([
            "status"   => "success",
            "customer" => $safeUser
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Invalid Email or Password"
        ]);
    }
} else {
    // Same message for non-existent email → prevents enumeration
    echo json_encode([
        "status"  => "error",
        "message" => "Invalid Email or Password"
    ]);
}

$stmt->close();
exit();
?>