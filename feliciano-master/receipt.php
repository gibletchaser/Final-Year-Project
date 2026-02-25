<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');

// DB connection (copy from place-order.php)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$session_id = $_GET['session_id'] ?? null;

if ($session_id) {
    try {
    $session = \Stripe\Checkout\Session::retrieve([
        'id' => $session_id,
        'expand' => ['line_items.data.price.product']
    ]);

    if ($session->payment_status !== 'paid') {
        echo "<h1>Payment not completed yet</h1>";
        echo "<p>Current status: " . htmlspecialchars($session->payment_status) . "</p>";
        echo "<a href='menu.php'>Back to menu</a>";
        exit;
    }

    $line_items = $session->line_items->data ?? [];

    // Define the missing variables here
    $customer_name  = $session->customer_details->name
                   ?? $session->metadata->customer_name
                   ?? $session->customer->name
                   ?? 'Valued Customer';

    $payment_intent = $session->payment_intent ?? '—';

    // Optional: you can also get email, total, etc. if you want to show more info
    // $email = $session->customer_details->email ?? '—';
    // $total  = number_format($session->amount_total / 100, 2);

} catch (Exception $e) {
    http_response_code(400);
    echo "<h1 style='color: #c00;'>Error loading payment details</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='menu.php'>Back to menu</a>";
    exit;
}
}       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Yobyong</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/dist/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-screen py-20">

    <div class="max-w-md w-full bg-white shadow-lg rounded-2xl p-8 text-center border border-gray-100">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
            <i class="fa-solid fa-check text-4xl text-green-600"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Thank You, <?php echo htmlspecialchars($customer_name); ?>!</h1>
        <p class="text-gray-500 mb-8">Your order is confirmed and payment was successful.</p>

        <div class="bg-gray-50 rounded-xl p-4 mb-8 text-left text-sm border border-gray-200">
            <div class="flex justify-between py-2 border-b border-gray-200">
                <span class="text-gray-400 uppercase font-semibold text-xs tracking-wider">Payment ID</span>
                <span class="text-gray-700 font-mono italic"><?php echo substr(htmlspecialchars($payment_intent), 0, 15) . '...'; ?></span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-400 uppercase font-semibold text-xs tracking-wider">Status</span>
                <span class="text-green-600 font-bold uppercase text-xs">Paid</span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="menu.php" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition shadow-md text-center">
             Back to Home
            </a>
    
            <a href="view_receipt.php?session_id=<?php echo $session_id; ?>" 
            class="block w-full bg-white border border-gray-300 text-center py-3 rounded-xl">
            View Receipt
            </a>
        </div>

        <p class="mt-8 text-xs text-gray-400">
            A confirmation email has been sent to your inbox.
        </p>
    </div>

</body>
</html>

