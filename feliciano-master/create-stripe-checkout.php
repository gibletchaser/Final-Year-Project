<?php
header('Content-Type: application/json');

require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');  // ← REPLACE with your sk_test_... (keep secret!)

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['amount'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid amount']);
    exit;
}

$amount_myr   = floatval($data['amount']);
$amount_cents = (int) ($amount_myr * 100);  // Stripe uses smallest unit (cents for MYR)

try {
    // Optional: Save pending order to DB first, get an order_id
    // For now, we'll pass metadata
    $session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card', 'fpx'],
    'line_items' => [[
        'price_data' => [
            'currency'     => 'myr',
            'product_data' => [
                'name' => 'Yob Yong Food Order',
                'description' => 'Order for ' . ($data['customer_name'] ?? 'Customer'),
            ],
            'unit_amount'  => $amount_cents,
        ],
        'quantity' => 1,
    ]],
    'mode'              => 'payment',
    'success_url' => 'http://localhost/yyos/Final-Year-Project/feliciano-master/receipt.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'        => 'http://localhost/yyos/Final-Year-Project/feliciano-master/menu.php?cancelled=1',
    'customer_email'    => null,
    'metadata'          => [
        'customer_name' => $data['customer_name'] ?? '',
        'phone'         => $data['phone'] ?? '',
        'notes'         => $data['notes'] ?? '',
        'cart_json'     => json_encode($data['cart'] ?? []),
    ],
]);

    echo json_encode([
        'success'   => true,
        'sessionId' => $session->id
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
?>