<?php
ob_start();
require_once 'vendor/autoload.php';
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

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
    echo json_encode(["success" => false, "message" => "Invalid JSON input"]);
    exit;
}

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
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

// ==========================================================
// NEW FIX STARTS HERE: Check for existing Stripe session
// ==========================================================
if (!empty($stripe_session_id)) {
    $check = $conn->prepare("SELECT id FROM orders WHERE stripe_session_id = ?");
    $check->bind_param("s", $stripe_session_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        $conn->close();
        ob_end_clean();
        // Return success so the user's browser moves to the next page
        echo json_encode([
            "success" => true, 
            "order_id" => $existing['id'], 
            "message" => "Order already recorded."
        ]);
        exit;
    }
    $check->close();
}
// ==========================================================
// NEW FIX ENDS HERE
// ==========================================================

// ────────────────────────────────────────────────
// Add this → get the customer ID from session
$customer_id = null;
if (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
    $customer_id = (int)$_SESSION['user']['id'];
}
// If you use a different session key, change it (examples below)
// if (isset($_SESSION['customer_id'])) { $customer_id = (int)$_SESSION['customer_id']; }
// if (isset($_SESSION['id']))           { $customer_id = (int)$_SESSION['id']; }

// Optional: force login (recommended for real shops)
// if ($customer_id === null) {
//     echo json_encode(["success" => false, "message" => "Please log in to place an order"]);
//     exit;
// }
// ────────────────────────────────────────────────

// Updated INSERT – add customer_id column and one more ?
$stmt = $conn->prepare("
    INSERT INTO orders (
        customer_name, phone, total_amount, 
        payment_method, stripe_session_id, notes, payment_status,
        customer_id                          ← new
    ) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)
");

if (!$stmt) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}

// Updated bind_param – add one more 'i' at the end (i = integer)
$stmt->bind_param("sssdssi", 
    $customer_name, 
    $phone, 
    $total, 
    $payment_method, 
    $stripe_session_id, 
    $notes,
    $customer_id               
);

if (!$stmt->execute()) {
    $error = $stmt->error ?: $conn->error;
    file_put_contents('place-order-errors.log', date('Y-m-d H:i:s') . " Order insert failed: " . $error . "\n", FILE_APPEND);
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Order creation failed: " . $error]);
    $stmt->close();
    $conn->close();
    exit;
}

$order_id = $conn->insert_id;
$stmt->close();

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
            $stmt->execute();
        }
        $stmt->close();
    }
}

$conn->close();
ob_end_clean();
echo json_encode([
    "success"   => true,
    "order_id"  => $order_id,
    "message"   => "Order placed successfully"
]);
exit;
?>