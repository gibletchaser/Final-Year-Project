<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$orderID = $data['orderID'] ?? '';

if (!$orderID) {
    echo json_encode(['error' => 'Missing order ID']);
    exit;
}

// Reuse same credentials as above
$clientId     = 'YOUR_SANDBOX_CLIENT_ID';
$clientSecret = 'YOUR_SANDBOX_SECRET';

// Get token (same code as above)
$ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$tokenResult = json_decode(curl_exec($ch), true);
curl_close($ch);

$accessToken = $tokenResult['access_token'] ?? null;
if (!$accessToken) {
    echo json_encode(['error' => 'Token failed']);
    exit;
}

// Capture
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
]);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

// Save to your DB if success
if (isset($response['status']) && $response['status'] === 'COMPLETED') {
    // TODO: Save transaction ID $response['id'], amount, status to your orders table
    // e.g. update your orders table with paypal_transaction_id = $response['id']
    // and status = 'paid'
}

echo $result;