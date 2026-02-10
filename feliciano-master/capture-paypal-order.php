<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$orderID = $input['orderID'] ?? '';

if (!$orderID) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing orderID']);
    exit;
}

$clientId     = "ATYkEEnovNtPctjWpE5ViGlEfEi8WhAplmEhklTwEFN6CAPNpZdDS-B0ZFJiCfxx60cRm508GOPC9sOa";  // ← your current one, but confirm it matches dashboard
$clientSecret = "EKkIfre7xHFjTQ0BGyeME17Z7Wfvhsd5iDFyOlBQ8wogus9hcQBmzefzv7a5DpIdimaC1PFgQoaHD-yz"; 

// Get access token
$ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_USERPWD => "$clientId:$clientSecret",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials"
]);

$tokenResponseRaw = curl_exec($ch);
$tokenErr = curl_error($ch);
$tokenHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($tokenErr || $tokenHttpCode !== 200) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Token request failed',
        'curl_error' => $tokenErr,
        'http_code' => $tokenHttpCode,
        'raw_response' => $tokenResponseRaw
    ]);
    exit;
}

$tokenData = json_decode($tokenResponseRaw, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'No access_token in response',
        'paypal_response' => $tokenData
    ]);
    exit;
}

// Now capture the order
// ... (keep the token part the same - we know it works)

// Capture the order
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/" . urlencode($orderID) . "/capture");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "Accept: application/json"
    ]
]);

// After setting up $ch for capture...
$captureResponseRaw = curl_exec($ch);
// After setting up $ch for capture...
$captureResponseRaw = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

// Log to file for visibility
$logEntry = date('Y-m-d H:i:s') . " - Capturing OrderID: " . $orderID . "\n" .
            "HTTP Code from PayPal: " . $httpCode . "\n" .
            "cURL Error: " . ($curlError ?: 'none') . "\n" .
            "Raw PayPal Response: " . $captureResponseRaw . "\n" .
            "----------------------------------------\n";

file_put_contents('paypal_capture_log.txt', $logEntry, FILE_APPEND);

if ($curlError || !in_array($httpCode, [200, 201])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Capture failed',
        'paypal_http' => $httpCode,
        'curl_error' => $curlError,
        'paypal_response' => $captureResponseRaw ? json_decode($captureResponseRaw, true) : 'No response'
    ]);
    exit;
}

// Success path...
$captureData = json_decode($captureResponseRaw, true);
if (isset($captureData['status']) && $captureData['status'] === 'COMPLETED') {
    // Save order to DB here...
    $your_order_id = "YOB-" . time();
    echo json_encode([
        'success' => true,
        'your_order_id' => $your_order_id,
        'paypal_id' => $captureData['id']
    ]);
} else {
    // Fallback if not COMPLETED
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Capture not completed',
        'details' => $captureData
    ]);
}// ... (keep the token part the same - we know it works)

// Capture the order
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/" . urlencode($orderID) . "/capture");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "Accept: application/json"
    ]
]);

// After setting up $ch for capture...
$captureResponseRaw = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

// Log to file for visibility
$logEntry = date('Y-m-d H:i:s') . " - Capturing OrderID: " . $orderID . "\n" .
            "HTTP Code from PayPal: " . $httpCode . "\n" .
            "cURL Error: " . ($curlError ?: 'none') . "\n" .
            "Raw PayPal Response: " . $captureResponseRaw . "\n" .
            "----------------------------------------\n";

file_put_contents('paypal_capture_log.txt', $logEntry, FILE_APPEND);

if ($curlError || !in_array($httpCode, [200, 201])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Capture failed',
        'paypal_http' => $httpCode,
        'curl_error' => $curlError,
        'paypal_response' => $captureResponseRaw ? json_decode($captureResponseRaw, true) : 'No response'
    ]);
    exit;
}

// Success path...
$captureData = json_decode($captureResponseRaw, true);
if (isset($captureData['status']) && $captureData['status'] === 'COMPLETED') {
    // Save order to DB here...
    $your_order_id = "YOB-" . time();
    echo json_encode([
        'success' => true,
        'your_order_id' => $your_order_id,
        'paypal_id' => $captureData['id']
    ]);
} else {
    // Fallback if not COMPLETED
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Capture not completed',
        'details' => $captureData
    ]);
}