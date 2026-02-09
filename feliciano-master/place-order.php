<?php
file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Received data: " . print_r($data, true) . "\n", FILE_APPEND);
error_log(print_r($_POST, true));
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
$customer_email = $_SESSION['email'] ?? 'guest@example.com'; // ← change if needed

$name           = $conn->real_escape_string($data['customer_name']);
$phone          = $conn->real_escape_string($data['phone']);
$notes          = $conn->real_escape_string($data['notes'] ?? '');
$total          = floatval($data['total_amount']);
$payment_method = $data['payment_method'];

// ────────────────────────────────────────────────
// NEW: Get PayPal transaction ID (only sent when PayPal was used)
$paypal_id = $data['paypal_transaction_id'] ?? null;
// ────────────────────────────────────────────────

// 1. Create order - ADD paypal_transaction_id to the query
$stmt = $conn->prepare("
    INSERT INTO orders (
        customer_email, customer_name, phone, total_amount, 
        payment_method, notes, paypal_transaction_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");

// ────────────────────────────────────────────────
// IMPORTANT: Add 's' for the new string field in bind_param
$stmt->bind_param(
    "sssdsss",  // ← added one more 's' for paypal_transaction_id
    $customer_email,
    $name,
    $phone,
    $total,
    $payment_method,
    $notes,
    $paypal_id      // ← new parameter
);
// ────────────────────────────────────────────────

$stmt->execute();

$order_id = $conn->insert_id;
$stmt->close();

// 2. Insert items (this part stays the same)
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

// ... after all executes ...

header('Content-Type: application/json; charset=utf-8');

// Make sure NOTHING is output before this (no echo, no space before <?php, no error messages)
echo json_encode([
    "success"  => true,
    "order_id" => $order_id,
    "message"  => "Order placed successfully"
]);

exit;   // important - stop any further output
?>