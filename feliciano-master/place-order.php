<?php
// ── 1. Silence errors & start buffer BEFORE anything else ────
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
ob_start();

// ── 2. Session before any output ─────────────────────────────
session_start();

// ── 3. Now safe to load external libs ────────────────────────
require_once 'vendor/autoload.php';

// ── 4. Force JSON response header ────────────────────────────
// Clear anything the autoloader may have leaked, then set header
ob_clean();
header('Content-Type: application/json');

// ── Helper: clean exit with JSON ─────────────────────────────
function jsonExit(array $payload): void {
    ob_end_clean();
    echo json_encode($payload);
    exit;
}

// ── 5. Read raw POST body ─────────────────────────────────────
$rawInput = file_get_contents('php://input');
$data     = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
    jsonExit(["success" => false, "message" => "Invalid JSON input"]);
}

if (empty($data['items'])) {
    jsonExit(["success" => false, "message" => "No items in order"]);
}

// ── 6. DB connection ──────────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "yobyong");
if ($conn->connect_error) {
    jsonExit(["success" => false, "message" => "Database connection failed"]);
}
$conn->set_charset("utf8mb4");

// ── 7. Get customer info ──────────────────────────────────────
// Session takes priority; frontend values are fallback
$customer_id   = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
$customer_name = $_SESSION['user']['name']  ?? trim($data['customer_name'] ?? '');
$phone         = (string)($_SESSION['user']['phone'] ?? trim($data['phone'] ?? ''));
$notes         = trim($data['notes']          ?? '');
$total         = floatval($data['total_amount'] ?? 0);
$payment_method    = trim($data['payment_method']    ?? 'stripe');
$stripe_session_id = trim($data['stripe_session_id'] ?? '') ?: null;

if (empty($customer_name) || $total <= 0) {
    jsonExit(["success" => false, "message" => "Missing required fields (name or total)"]);
}

// ── 8. Prevent duplicate Stripe orders ───────────────────────
if ($stripe_session_id !== null) {
    $chk = $conn->prepare("SELECT id FROM orders WHERE stripe_session_id = ? LIMIT 1");
    $chk->bind_param("s", $stripe_session_id);
    $chk->execute();
    $chk->store_result();

    if ($chk->num_rows > 0) {
        $chk->bind_result($existing_id);
        $chk->fetch();
        $chk->close();
        $conn->close();
        jsonExit([
            "success"  => true,
            "order_id" => $existing_id,
            "message"  => "Order already recorded."
        ]);
    }
    $chk->close();
}

// ── 9. Generate unique order code ────────────────────────────
// Keep trying until we get one that doesn't exist yet
do {
    $order_code = 'ORD-' . strtoupper(substr(uniqid('', true), -6));
    $dup = $conn->prepare("SELECT id FROM orders WHERE order_code = ? LIMIT 1");
    $dup->bind_param("s", $order_code);
    $dup->execute();
    $dup->store_result();
    $taken = $dup->num_rows > 0;
    $dup->close();
} while ($taken);

// ── 10. Insert order ──────────────────────────────────────────
$stmt = $conn->prepare("
    INSERT INTO orders
        (order_code, customer_id, customer_name, phone, notes,
         total_amount, payment_method, stripe_session_id,
         payment_status, order_status)
    VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'Pending')
");

if (!$stmt) {
    $conn->close();
    jsonExit(["success" => false, "message" => "Prepare failed: " . $conn->error]);
}

// bind: s=order_code, i=customer_id, s=name, s=phone, s=notes,
//       d=total, s=payment_method, s=stripe_session_id
$stmt->bind_param(
    "sisssdss",
    $order_code,
    $customer_id,
    $customer_name,
    $phone,
    $notes,
    $total,
    $payment_method,
    $stripe_session_id
);

if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    $conn->close();
    jsonExit(["success" => false, "message" => "Order insert failed: " . $err]);
}

$order_id = $conn->insert_id;
$stmt->close();

// ── 11. Insert order items ────────────────────────────────────
$itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, menu_id, name, price, quantity)
    VALUES (?, ?, ?, ?, ?)
");

if ($itemStmt) {
    foreach ($data['items'] as $item) {
        $menu_id  = (int)($item['id']       ?? 0);
        $name     = trim($item['name']      ?? 'Unknown');
        $price    = floatval($item['price'] ?? 0);
        $qty      = (int)($item['quantity'] ?? 1);

        // Skip malformed items silently
        if ($menu_id <= 0 || $price <= 0 || $qty <= 0) continue;

        $itemStmt->bind_param("iisdi", $order_id, $menu_id, $name, $price, $qty);
        $itemStmt->execute();
    }
    $itemStmt->close();
}

$conn->close();

// ── 12. Success ───────────────────────────────────────────────
jsonExit([
    "success"    => true,
    "order_id"   => $order_id,
    "order_code" => $order_code,
    "message"    => "Order placed successfully"
]);
?>