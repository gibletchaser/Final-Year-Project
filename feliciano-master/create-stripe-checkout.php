<?php
// create-stripe-checkout.php

// -----------------------------
// SAFE JSON OUTPUT SETTINGS
// -----------------------------
ini_set('display_errors', 0);
error_reporting(0);

ob_start();
header('Content-Type: application/json');

// -----------------------------
// LOAD STRIPE
// -----------------------------
require_once __DIR__ . '/vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_YOUR_SECRET_KEY_HERE'); // ← Replace with your real secret key

// -----------------------------
// HELPER FUNCTION
// -----------------------------
function jsonExit($data, $status = 200) {
    http_response_code($status);
    ob_clean();
    echo json_encode($data);
    exit;
}

// -----------------------------
// ONLY ALLOW POST
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonExit([
        "success" => false,
        "error" => "Invalid request method"
    ], 405);
}

// -----------------------------
// READ JSON INPUT
// -----------------------------
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    jsonExit([
        "success" => false,
        "error" => "Invalid JSON payload"
    ], 400);
}

// -----------------------------
// VALIDATE DATA
// -----------------------------
$name   = trim($input['customer_name'] ?? '');
$phone  = trim($input['phone'] ?? '');
$notes  = trim($input['notes'] ?? '');
$cart   = $input['cart'] ?? [];
$amount = floatval($input['amount'] ?? 0);

if (!$name || !$phone) {
    jsonExit([
        "success" => false,
        "error" => "Missing customer details"
    ], 400);
}

if (empty($cart)) {
    jsonExit([
        "success" => false,
        "error" => "Cart is empty"
    ], 400);
}

// -----------------------------
// BUILD STRIPE LINE ITEMS
// -----------------------------
$line_items = [];

foreach ($cart as $item) {

    $item_name = trim($item['name'] ?? 'Item');
    $price     = floatval($item['price'] ?? 0);
    $qty       = intval($item['quantity'] ?? 1);

    if ($price <= 0 || $qty <= 0) {
        continue;
    }

    $line_items[] = [
        "price_data" => [
            "currency" => "usd",   // Change to MYR if needed
            "product_data" => [
                "name" => $item_name
            ],
            "unit_amount" => intval($price * 100) // Stripe uses cents
        ],
        "quantity" => $qty
    ];
}

if (empty($line_items)) {
    jsonExit([
        "success" => false,
        "error" => "Invalid cart items"
    ], 400);
}

// -----------------------------
// CREATE STRIPE SESSION
// -----------------------------
try {

    $session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "mode" => "payment",

        "line_items" => $line_items,

        "metadata" => [
            "customer_name" => $name,
            "phone" => $phone,
            "notes" => $notes
        ],

        "success_url" => "http://localhost/yourproject/receipt.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url"  => "http://localhost/yourproject/index.php?payment=cancelled"
    ]);

    jsonExit([
        "success" => true,
        "sessionId" => $session->id
    ]);

} catch (\Stripe\Exception\ApiErrorException $e) {

    error_log("Stripe API error: " . $e->getMessage());

    jsonExit([
        "success" => false,
        "error" => "Stripe API error"
    ], 500);

} catch (Exception $e) {

    error_log("Stripe session error: " . $e->getMessage());

    jsonExit([
        "success" => false,
        "error" => "Server error"
    ], 500);
}
