<?php
header('Content-Type: text/plain');

$clientId     = "ATYkEEnovNtPctjWpE5ViGlEfEi8WhAplmEhklTwEFN6CAPNpZdDS-B0ZFJiCfxx60cRm508GOPC9sOa";  // ← your current one, but confirm it matches dashboard
$clientSecret = "EKkIfre7xHFjTQ0BGyeME17Z7Wfvhsd5iDFyOlBQ8wogus9hcQBmzefzv7a5DpIdimaC1PFgQoaHD-yz";   // ← paste the fresh secret


$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_USERPWD => "$clientId:$clientSecret",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials"
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "cURL Error: " . $err;
} else {
    echo "Raw response:\n" . $response . "\n\n";
    $data = json_decode($response, true);
    if (isset($data['access_token'])) {
        echo "SUCCESS! Access Token: " . $data['access_token'];
    } else {
        echo "FAILED – check error in response.";
    }
}