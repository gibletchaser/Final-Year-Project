<?php
header('Content-Type: application/json');

// Your PayPal credentials
$clientId     = 'YOUR_SANDBOX_CLIENT_ID';
$clientSecret = 'YOUR_SANDBOX_SECRET';

$data = json_decode(file_get_contents('php://input'), true);
$amount = $data['total'] ?? 0;
$currency = $data['currency'] ?? 'MYR';

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

// Get access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

$result = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($result, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    echo json_encode(['error' => 'Failed to get PayPal token']);
    exit;
}

// Create order
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'amount' => [
            'currency_code' => $currency,
            'value' => number_format($amount, 2, '.', '')
        ],
        'description' => $data['description'] ?? 'Yob Yong Order'
    ]]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
]);

$result = curl_exec($ch);
curl_close($ch);

echo $result; // PayPal returns { "id": "order_xxx", ... }