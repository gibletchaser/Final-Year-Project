<?php
ob_start();
require_once 'vendor/autoload.php';
// Suppress ALL display of errors/notices to browser
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL); // still log them

session_start();
header('Content-Type: application/json');

// Log raw input for debugging (to file only)
$rawInput = file_get_contents('php://input');
file_put_contents('place-order-debug.log', date('Y-m-d H:i:s') . " RAW INPUT: " . $rawInput . "\n", FILE_APPEND);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Invalid JSON input: " . json_last_error_msg()]);
    exit;
}

file_put_contents('place-order-debug.log', date('Y-m-d H:i:s') . " Parsed data: " . print_r($data, true) . "\n", FILE_APPEND);

if (!$data || empty($data['items'])) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "No items in order"]);
    exit;
}

$customer_name   = $conn->real_escape_string($data['customer_name'] ?? '');
$phone  = $conn->real_escape_string($data['phone'] ?? '');
$notes  = $conn->real_escape_string($data['notes'] ?? '');
$total  = floatval($data['total_amount'] ?? 0);
$payment_method = $data['payment_method'] ?? 'stripe';
$stripe_session_id = $data['stripe_session_id'] ?? null;

if (empty($customer_name) || empty($phone) || $total <= 0) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Missing required fields or invalid total"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO orders (
        customer_name, phone, total_amount, 
        payment_method, stripe_session_id, notes, payment_status
    ) VALUES (  ?, ?, ?, ?, ?, ?, 'pending')
");

if (!$stmt) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("sssdss", $customer_name, $phone, $total, $payment_method, $stripe_session_id, $notes);

if (!$stmt->execute()) {
    $error = $stmt->error ?: $conn->error;
    file_put_contents('place-order-errors.log', date('Y-m-d H:i:s') . " Order insert failed: " . $error . "\nData: " . print_r($data, true) . "\n", FILE_APPEND);
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Order creation failed: " . $error]);
    $stmt->close();
    $conn->close();
    exit;
}

$order_id = $conn->insert_id;
$stmt->close();

// Items insert (skip if table doesn't exist yet – but you should create it)
if (!empty($data['items'])) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_id, name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        foreach ($data['items'] as $item) {
            $menu_id  = (int)($item['id'] ?? 0);
            $itemName = $conn->real_escape_string($item['name'] ?? 'Unknown');
            $price    = floatval($item['price'] ?? 0);
            $qty      = (int)($item['quantity'] ?? 1);

            if ($menu_id <= 0 || $price <= 0 || $qty <= 0) continue;

            $stmt->bind_param("iisdi", $order_id, $menu_id, $itemName, $price, $qty);
            $stmt->execute(); // ignore per-item errors for now
        }
        $stmt->close();
    }
}

$conn->close();
ob_end_clean();
echo json_encode([
    "success"   => true,
    "order_id"  => $order_id,
    "message"   => "Order placed successfully (pending payment if using Stripe)"
]);

exit;

?>