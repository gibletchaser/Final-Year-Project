<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');

$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    http_response_code(400);
    die("No order information found. <a href='menu.php'>Back to menu</a>");
}

try {
    $session = \Stripe\Checkout\Session::retrieve([
        'id' => $session_id,
        'expand' => ['line_items.data.price.product']
    ]);

    $status_text = match ($session->status) {
        'complete' => '✅ Payment successful! Your order is confirmed.',
        'open'     => '⏳ Order is pending (still processing).',
        'expired'  => '❌ Order expired. Please try again.',
        default    => 'Unknown status.'
    };

    $payment_status = $session->payment_status ?? 'unknown';

    $customer_name = $session->customer_details->name ?? 'Cutie customer ♡';
    $total    = $session->amount_total / 100;
    $currency = strtoupper($session->currency ?? 'myr');
    $symbol   = ($currency === 'MYR') ? 'RM' : '$';

    $date     = date('d M Y, h:i A', $session->created);
    $ref      = substr($session_id, -8); // or use payment_intent if available

    $line_items = $session->line_items->data ?? [];

} catch (\Exception $e) {
    http_response_code(400);
    die("Sorry, couldn't load your order: " . htmlspecialchars($e->getMessage()) .
        "<br><a href='menu.php'>Back to menu</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Status ♡ - YOBYBYONG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&family=Patrick+Hand&family=Reenie+Beanie&display=swap" rel="stylesheet">

    <style>
        body { background: linear-gradient(to bottom, #fff5f5, #fffaf0); font-family: 'Comic Neue', cursive; }
        .status-card {
            background: #fffaf0;
            border: 3px dashed #ff9aa2;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(255,182,193,0.3);
            max-width: 420px;
            margin: 2rem auto;
            padding: 1.5rem;
        }
        .title { font-family: 'Patrick Hand', cursive; font-size: 2.8rem; color: #d6336c; text-align: center; }
        .status-badge {
            font-size: 1.3rem;
            padding: 0.6rem 1.2rem;
            border-radius: 999px;
            display: inline-block;
            margin: 1rem auto;
        }
        .good { background: #d4f4e2; color: #2f855a; }
        .pending { background: #fefcbf; color: #744210; }
        .bad { background: #fed7d7; color: #9b2c2c; }
    </style>
</head>
<body class="min-h-screen py-10 px-4">

<div class="status-card text-center">

    <h1 class="title">YOBYBYONG ♡</h1>
    <p class="text-lg text-pink-600 mb-6">Your Order Status</p>

    <div class="status-badge <?= $session->status === 'complete' ? 'good' : ($session->status === 'open' ? 'pending' : 'bad') ?>">
        <?= $status_text ?>
    </div>

    <?php if ($session->status === 'complete' && $payment_status === 'paid'): ?>
        <p class="text-xl font-bold text-pink-700 mt-4">Payment Received! 🎉</p>
    <?php endif; ?>

    <div class="mt-6 text-left space-y-2 text-sm">
        <p><strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
        <p><strong>Ref #:</strong> #<?= strtoupper($ref) ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($customer_name) ?></p>
    </div>

    <hr class="border-dashed border-pink-300 my-6">

    <div class="text-left">
        <p class="font-bold mb-2">Items Ordered:</p>
        <?php foreach ($line_items as $item): ?>
            <p class="text-pink-800">
                <?= htmlspecialchars($item->description ?? $item->price->product->name ?? 'Item') ?>
                × <?= $item->quantity ?>
                — <?= $symbol . number_format($item->amount_total / 100, 2) ?>
            </p>
        <?php endforeach; ?>
    </div>

    <hr class="border-dashed border-pink-300 my-6">

    <p class="text-2xl font-black text-pink-700">
        Total: <?= $symbol . number_format($total, 2) ?>
    </p>

    <p class="mt-10 text-lg text-pink-600">
        Thank you so much! ♡<br>
        We'll prepare your order soon~ ૮₍ ˶•ᴗ•˶ ₎ა
    </p>

</div>

<div class="text-center mt-8">
    <a href="menu.php" class="bg-pink-400 text-white px-8 py-3 rounded-xl font-bold shadow hover:bg-pink-500 transition">
        ← Back to Menu
    </a>
</div>

</body>
</html>