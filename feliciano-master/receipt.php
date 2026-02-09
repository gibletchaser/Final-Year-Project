<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php?redirect=receipt");
    exit;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    die("Invalid order.");
}
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE id = ? AND customer_email = ?
");
$stmt->bind_param("is", $order_id, $_SESSION['email']);   // or $_SESSION['user_id'] if using ID
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found or you do not have permission to view it.");
}
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $order_id);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$order = $stmt->get_result()->fetch_assoc();
if (!$order) {
    http_response_code(404);
    die("Order not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt #<?php echo $order_id; ?> - Yob Yong</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h1 { color: #e67e22; text-align: center; }
        table { margin: 20px 0; border-collapse: collapse; width: 100%; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #f8f8f8; }
        .total { font-size: 1.3em; background: #fff3e0; }
        .success-box {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="success-box">
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been received and is being prepared.</p>
</div>

<p><strong>Order Number:</strong> #<?php echo $order_id; ?></p>
<p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
<p><strong>Placed on:</strong> <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
<p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>

<?php if ($order['payment_method'] === 'paypal'): ?>
<p><strong>PayPal Transaction ID:</strong> <?php echo htmlspecialchars($order['paypal_transaction_id'] ?? '—'); ?></p>
<?php endif; ?>

<h3>Order Items</h3>
</body>
</html>