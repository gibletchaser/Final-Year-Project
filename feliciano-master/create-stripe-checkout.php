<?php
header('Content-Type: application/json');

require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');

$conn = new mysqli("localhost", "root", "", "yobyong");
if ($conn->connect_error) {
    echo json_encode(['success'=>false, 'error'=>'DB failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['amount']) || empty($data['cart'])) {
    echo json_encode(['success'=>false, 'error'=>'Invalid amount or empty cart']);
    exit;
}

$amount_myr   = floatval($data['amount']);
$customer_name = trim($data['customer_name'] ?? '');
$phone         = trim($data['phone'] ?? '');
$notes         = trim($data['notes'] ?? '');
$cart_json     = json_encode($data['cart']);

// Build line items from cart (this is the fix)
$line_items = [];
$calculated_total = 0;

foreach ($data['cart'] as $item) {
    $name      = trim($item['name'] ?? $item['title'] ?? 'Item');
    $price     = floatval($item['price'] ?? 0);
    $quantity  = (int)($item['quantity'] ?? 1);

    if ($price <= 0 || $quantity < 1 || empty($name)) continue;

    $line_items[] = [
        'price_data' => [
            'currency'     => 'myr',
            'product_data' => ['name' => $name],
            'unit_amount'  => (int)($price * 100),
        ],
        'quantity' => $quantity,
    ];

    $calculated_total += $price * $quantity;
}

// Optional: verify frontend total matches backend calculation (anti-tampering)
if (abs($calculated_total - $amount_myr) > 0.01) {
    echo json_encode(['success'=>false, 'error'=>'Amount mismatch']);
    exit;
}

if (empty($line_items)) {
    echo json_encode(['success'=>false, 'error'=>'No valid cart items']);
    exit;
}

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card','fpx'],
        'line_items'           => $line_items,
        'mode'                 => 'payment',
        'success_url'          => 'http://localhost/yyos/Final-Year-Project/feliciano-master/receipt.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'           => 'http://localhost/yyos/Final-Year-Project/feliciano-master/menu.php',
        'metadata'             => [
            'customer_name' => $customer_name,
            'phone'         => $phone,
            'cart'          => $cart_json
        ]
    ]);

    // Insert into DB (your existing code – looks fine)
    $stmt = $conn->prepare("
        INSERT INTO orders 
        (customer_name, phone, notes, total_amount, items, stripe_session_id, payment_status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
    ");

    $stmt->bind_param("sssiss", $customer_name, $phone, $notes, $amount_myr, $cart_json, $session->id);
    $stmt->execute();
    $stmt->close();

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

$conn->close();
?>