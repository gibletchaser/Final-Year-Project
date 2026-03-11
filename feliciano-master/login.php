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

// Now also select id so we can store it in session
$stmt = $conn->prepare("SELECT id, name, email, phone, password FROM customer WHERE email = ?");
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

if ($customer = $result->fetch_assoc()) {
    $input_hashed = hash('sha256', $pass);

    if ($input_hashed === $customer['password']) {

        // Store full customer info in session including id
        $_SESSION['customer'] = [
            "id"    => $customer['id'],
            "name"  => $customer['name'],
            "email" => $customer['email'],
            "phone" => $customer['phone'],
            'profilePic' => $customer['profilePic']
        ];

        // Also set user key so place-order.php and order_history.php work
        $_SESSION['user'] = [
            "id"    => $customer['id'],
            "name"  => $customer['name'],
            "email" => $customer['email'],
            "phone" => $customer['phone']
        ];

        $safeUser = [
            "id"    => $customer['id'],
            "name"  => $customer['name'],
            "email" => $customer['email'],
            "phone" => $customer['phone']
        ];

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
    echo json_encode([
        "status"  => "error",
        "message" => "Invalid Email or Password"
    ]);
}

$stmt->close();
exit();
?>