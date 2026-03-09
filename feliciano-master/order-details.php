<?php
// order-details.php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$user = $_SESSION['user'];

$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE id = ? 
      AND customer_name = ? 
      AND phone = ?
");
$stmt->execute([$id, $user['name'], $user['phone']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found or you don't have permission to view it.");
}
?>

<!-- Similar HTML structure as above, but showing one order in detail -->
<h2>Order #<?= $order['id'] ?></h2>
<p><strong>Date:</strong> <?= date('d M Y h:i A', strtotime($order['created_at'])) ?></p>
<p><strong>Items:</strong><br><pre><?= htmlspecialchars($order['items']) ?></pre></p>
<p><strong>Total:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>
<p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?> (<?= htmlspecialchars($order['payment_status']) ?>)</p>
<p><strong>Status:</strong> <?= htmlspecialchars($order['order_status'] ?? 'Pending') ?></p>
<p><strong>Notes:</strong><br><?= nl2br(htmlspecialchars($order['notes'] ?? '-')) ?></p>

<a href="order-history.php" class="btn btn-secondary">Back to History</a>