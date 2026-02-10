<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
echo json_encode(['id' => 'TEST-ORDER-' . time()]);
exit;

// Read cart from wherever you store it (localStorage → you must send it or use session)
$input = json_decode(file_get_contents('php://input'), true);

// In real code → get cart from session / database / or require it from client (but validate!)
$cart = json_decode($_COOKIE['cart'] ?? '[]', true);   // ← example – improve this!

if (empty($cart)) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

// Calculate real total (NEVER trust client total!)
$total = 0;
foreach ($cart as $item) {
    $total += (float)$item['price'] * (int)$item['quantity'];
}

if ($total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

// ────────────────────────────────────────────────
//  PayPal credentials  (move to config file later!)
$clientId     = "ATYkEEnovNtPctjWpE5ViGlEfEi8WhAplmEhklTwEFN6CAPNpZdDS-B0ZFJiCfxx60cRm508GOPC9sOa";  // ← your current one, but confirm it matches dashboard
$clientSecret = "EKkIfre7xHFjTQ0BGyeME17Z7Wfvhsd5iDFyOlBQ8wogus9hcQBmzefzv7a5DpIdimaC1PFgQoaHD-yz";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_USERPWD => "$clientId:$clientSecret",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials"
]);

$response = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($response, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    http_response_code(500);
    echo json_encode(['error' => 'Cannot get PayPal token']);
    exit;
}

// Create order
$payload = [
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'amount' => [
            'currency_code' => 'MYR',
            'value'         => number_format($total, 2, '.', '')
        ],
        'description' => 'Yob Yong Food Order'
    ]]
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$result = curl_exec($ch);
curl_close($ch);

echo $result;   // PayPal returns { "id": "...", ... }