<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');

$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    http_response_code(400);
    die("No session ID provided. <a href='menu.php'>Back to menu</a>");
}

try {
    $session = \Stripe\Checkout\Session::retrieve([
        'id' => $session_id,
        'expand' => ['line_items.data.price.product']
    ]);

    $line_items = $session->line_items->data ?? [];

    $customer_name = $session->customer_details->name
        ?? $session->metadata->customer_name
        ?? 'Customer';

    $total    = $session->amount_total / 100;
    $currency = strtoupper($session->currency ?? 'myr');
    $symbol   = ($currency === 'MYR') ? 'RM' : '$';

    $date     = date('d M Y, h:i A', $session->created);

    // Safe order/ref number
    $ref = $session->payment_intent
        ? substr($session->payment_intent, -8)
        : substr($session_id, -8);

} catch (\Exception $e) {
    http_response_code(400);
    die("Cannot load receipt: " . htmlspecialchars($e->getMessage()) .
        "<br><a href='menu.php'>Back to menu</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Yobyong</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 px-4">

<div class="max-w-sm mx-auto bg-white p-6 shadow-lg rounded border-t-4 border-black">

    <div class="text-center mb-6">
        <h1 class="text-3xl font-black uppercase">YOBYONG</h1>
        <p class="text-xs text-gray-600">Authentic Kitchen</p>
    </div>

    <div class="text-sm mb-6 space-y-1 font-mono">
        <div class="flex justify-between">
            <span>Date:</span> <span><?= htmlspecialchars($date) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Ref:</span> <span>#<?= strtoupper($ref) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Customer:</span> <span><?= htmlspecialchars($customer_name) ?></span>
        </div>
    </div>

    <hr class="border-dashed my-4">

    <table class="w-full text-sm mb-6 font-mono">
        <thead>
            <tr class="border-b">
                <th class="text-left pb-2">Item</th>
                <th class="text-right pb-2">Price</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($line_items as $item): ?>
            <tr class="border-b last:border-0">
                <td class="py-2">
                    <?= htmlspecialchars($item->price->product->name ?? $item->description ?? 'Item') ?>
                    <div class="text-gray-500 text-xs">Qty: <?= $item->quantity ?></div>
                </td>
                <td class="py-2 text-right">
                    <?= number_format($item->amount_total / 100, 2) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr class="border-dashed my-4">

    <div class="text-right font-bold text-lg">
        Total: <?= $symbol . number_format($total, 2) ?> (<?= $currency ?>)
    </div>

    <div class="text-center mt-10 text-sm">
        <p class="font-bold">Thank You!</p>
        <p class="text-gray-500">Please come again ♡</p>
    </div>
</div>

<div class="max-w-sm mx-auto mt-8 text-center no-print">
    <button onclick="window.print()" class="bg-black text-white px-6 py-3 rounded w-full mb-3">
        🖨️ Print Receipt
    </button>
    <a href="menu.php" class="text-gray-600 hover:underline">← Back to Menu</a>
</div>

</body>
</html>