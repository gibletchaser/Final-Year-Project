<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['items'])) {
    echo json_encode(["success" => false, "message" => "No items in order"]);
    exit;
}

// Get logged in user email (from your current login system)
$customer_email = $_SESSION['email'] ?? 'guest@example.com'; // ← change to your real session key

$name           = $conn->real_escape_string($data['customer_name']);
$phone          = $conn->real_escape_string($data['phone']);
$notes          = $conn->real_escape_string($data['notes'] ?? '');
$total          = floatval($data['total_amount']);
$payment_method = $data['payment_method'];

// 1. Create order
$stmt = $conn->prepare("
    INSERT INTO orders (customer_email, customer_name, phone, total_amount, payment_method, notes)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssds s", $customer_email, $name, $phone, $total, $payment_method, $notes);
$stmt->execute();

$order_id = $conn->insert_id;
$stmt->close();

// 2. Insert items
$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, menu_id, name, price, quantity)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($data['items'] as $item) {
    $menu_id  = (int)$item['id'];
    $itemName = $conn->real_escape_string($item['name']);
    $price    = floatval($item['price']);
    $qty      = (int)$item['quantity'];

    $stmt->bind_param("iisdi", $order_id, $menu_id, $itemName, $price, $qty);
    $stmt->execute();
}

$stmt->close();

echo json_encode([
    "success"  => true,
    "order_id" => $order_id,
    "message"  => "Order placed successfully"
]);