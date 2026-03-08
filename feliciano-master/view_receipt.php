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
        ?? 'shyna ♡';

    $total    = $session->amount_total / 100;
    $currency = strtoupper($session->currency ?? 'myr');
    $symbol   = ($currency === 'MYR') ? 'RM' : '$';

    $date     = date('d/m/Y', $session->created);
    $time     = date('h:i A', $session->created);

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
    <title>YOBYBYONG Receipt ♡</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,400;0,700;1,400&family=Patrick+Hand&family=Reenie+Beanie&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink-soft: #ffccd5;
            --pink-mid:  #ff9aa2;
            --pink-dark: #d6336c;
            --bg-cream:  #fffaf0;
        }
        body {
            background: linear-gradient(to bottom, #fff5f5, #fffaf0);
            font-family: 'Comic Neue', cursive;
        }
        .receipt {
            background: var(--bg-cream);
            border: 2px dashed var(--pink-soft);
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(255, 182, 193, 0.25);
            max-width: 400px;
            margin: 0 auto;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #ffe4e6, #ffd3e0);
            padding: 1.25rem 1rem;
            text-align: center;
            position: relative;
        }
        .shop-name {
            font-family: 'Patrick Hand', cursive;
            font-size: 2.8rem;
            color: var(--pink-dark);
            text-shadow: 2px 2px 0 white, 4px 4px 0 var(--pink-soft);
            transform: rotate(-1.5deg);
            letter-spacing: -1px;
        }
        .tagline {
            font-family: 'Reenie Beanie', cursive;
            font-size: 1.5rem;
            color: #c71585;
            margin-top: -0.6rem;
            transform: rotate(2deg);
        }
        .sticker {
            position: absolute;
            font-size: 2rem;
            opacity: 0.9;
        }
        .info-table, .items-table, .totals-table {
            width: 100%;
            font-size: 0.95rem;
            color: #333;
        }
        .items-table th, .items-table td {
            padding: 0.35rem 0;
            vertical-align: top;
        }
        .dotted-divider {
            border-top: 2px dotted var(--pink-mid);
            margin: 0.9rem 0;
            position: relative;
        }
        .dotted-divider::before,
        .dotted-divider::after {
            content: "♡";
            position: absolute;
            color: var(--pink-soft);
            font-size: 1.1rem;
        }
        .dotted-divider::before { left: 8px; top: -10px; }
        .dotted-divider::after  { right: 8px; top: -10px; }
        .item-desc {
            color: var(--pink-dark);
            font-weight: 600;
        }
        .price {
            color: #000;
            font-weight: 700;
        }
        .total-line {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--pink-dark);
        }
        .thanks {
            font-family: 'Reenie Beanie', cursive;
            font-size: 2.4rem;
            color: #ff6b81;
            text-align: center;
            transform: rotate(-3deg);
            margin: 1.2rem 0 0.4rem;
        }
        .footer {
            text-align: center;
            font-size: 0.85rem;
            color: #555;
            padding: 0 1rem 1.2rem;
            line-height: 1.4;
        }
        @media print {
            body { background: white; }
            .receipt { border: 1px solid #ffb6c1; box-shadow: none; }
            .no-print { display: none !important; }
            .header { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="min-h-screen py-10 px-4 sm:px-6">

<div class="receipt">

    <div class="header">
        <div class="sticker top-3 left-5">🍥</div>
        <div class="sticker top-4 right-6">✿</div>

        <h1 class="shop-name">YOBYONG</h1>
        <p class="tagline">Ordering Kitchen ♡</p>
    </div>

    <div class="p-5 pt-4">

        <!-- Header info -->
        <table class="info-table mb-4">
            <tr>
                <td>Date:</td>
                <td class="text-right"><?= htmlspecialchars($date) ?></td>
            </tr>
            <tr>
                <td>Time:</td>
                <td class="text-right"><?= htmlspecialchars($time) ?></td>
            </tr>
            <tr>
                <td>Ref #:</td>
                <td class="text-right font-bold">#<?= strtoupper($ref) ?></td>
            </tr>
            <tr>
                <td>Customer:</td>
                <td class="text-right"><?= htmlspecialchars($customer_name) ?></td>
            </tr>
        </table>

        <div class="dotted-divider"></div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr class="border-b border-dashed border-pink-300">
                    <th class="text-left pb-1">Item</th>
                    <th class="text-right pb-1">Qty</th>
                    <th class="text-right pb-1">Price</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($line_items as $item): 
                $name = htmlspecialchars($item->price->product->name ?? $item->description ?? 'Menu item');
                $qty  = $item->quantity;
                $price = $item->amount_total / 100;
            ?>
                <tr>
                    <td class="item-desc py-1 pr-2"><?= $name ?></td>
                    <td class="text-right py-1">× <?= $qty ?></td>
                    <td class="price text-right py-1"><?= $symbol . number_format($price, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="dotted-divider"></div>

        <!-- Totals -->
        <table class="totals-table text-right">
            <tr>
                <td class="w-3/5"></td>
                <td>Total:</td>
                <td class="total-line pl-3"><?= $symbol . number_format($total, 2) ?></td>
            </tr>
        </table>

        <div class="thanks">Thank You So Much ♡</div>
        <p class="text-center text-pink-600 -mt-3 mb-4">please come again soon~ ♡₊˚</p>

    </div>

    <div class="footer text-xs">
        YOBYBYONG Ordering Kitchen<br>
        UTM Residensi and Hotel ✿<br>
        Come back anytime ૮₍ ˶•ᴗ•˶ ₎ა ♡
    </div>

</div>

<!-- Action buttons -->
<div class="max-w-[400px] mx-auto mt-8 flex flex-col sm:flex-row gap-4 no-print">
    <button id="downloadPdf"
            class="bg-gradient-to-r from-pink-400 to-rose-400 text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:shadow-xl transition flex-1 text-lg flex items-center justify-center gap-2">
        🖨️ Download PDF 
    </button>
</div>

<div class="text-center mt-6 no-print">
    <a href="menu.php" class="text-pink-600 hover:text-pink-800 font-medium underline">← back to menu</a>
</div>

<script>
document.getElementById('downloadPdf').addEventListener('click', () => {
    const element = document.querySelector('.receipt');
    const opt = {
        margin:       [0.6, 0.4, 0.6, 0.4],
        filename:     `yobybyong_receipt_#<?= strtoupper($ref) ?>.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2.4, useCORS: true, backgroundColor: '#fffaf0' },
        jsPDF:        { unit: 'in', format: [10, 10], orientation: 'landscape' }
    };
    html2pdf().set(opt).from(element).save();
});
</script>

<script>
    // Run after page load
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const sessionId = urlParams.get('session_id');
        
        if (sessionId) {
            localStorage.setItem('latestYobyongOrderSession', sessionId);
            // Optional: console.log('Saved session:', sessionId);
        }
    });
</script>

</body>
</html>