<?php
session_start();
include 'db.php'; // your connection file

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    die("Invalid order.");
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$itemsStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemsStmt->bind_param("i", $order_id);
$itemsStmt->execute();
$items = $itemsStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="css/style.css"> <!-- your styles -->
</head>
<body>

<h1>Thank You! Order #<?php echo $order_id; ?></h1>

<p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
<p><strong>Date:</strong> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
<p><strong>Payment:</strong> <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
<p><strong>Status:</strong> <strong><?php echo strtoupper($order['status']); ?></strong></p>

<h3>Your Items</h3>
<table border="1" style="width:100%; border-collapse: collapse;">
    <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    <?php while($item = $items->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td>$<?php echo number_format($item['price'], 2); ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
    </tr>
    <?php endwhile; ?>
    <tr>
        <td colspan="3"><strong>Grand Total</strong></td>
        <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
    </tr>
</table>

<a href="my_orders.php">→ View All My Orders & Status</a>

</body>
</html>