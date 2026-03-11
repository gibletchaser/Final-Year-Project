<?php
session_start();
require_once 'db.php';

// Guard — must be logged in
if (!isset($_SESSION['user']['id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$order_id    = (int)$_GET['id'];
$customer_id = (int)$_SESSION['user']['id'];

// Fetch order — only show if it belongs to this customer
$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE id = ? AND customer_id = ?
");
$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found or you don't have permission to view it.");
}

// Fetch items
$stmt2 = $conn->prepare("
    SELECT oi.*, m.image
    FROM order_items oi
    LEFT JOIN menu m ON m.id = oi.menu_id
    WHERE oi.order_id = ?
");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

// Status badge colour
$s = strtolower($order['order_status'] ?? 'pending');
$badgeClass = match($s) {
    'completed'           => 'bg-success',
    'cancelled'           => 'bg-danger',
    'processing', 'ready' => 'bg-primary',
    default               => 'bg-warning text-dark'
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order <?= htmlspecialchars($order['order_code']) ?> - Yobyong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5" style="max-width: 680px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Order <?= htmlspecialchars($order['order_code']) ?></h2>
        <span class="badge <?= $badgeClass ?> fs-6">
            <?= htmlspecialchars(ucfirst($order['order_status'] ?? 'Pending')) ?>
        </span>
    </div>

    <!-- Order info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Date</small>
                    <p class="mb-1"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="col-6">
                    <small class="text-muted">Customer</small>
                    <p class="mb-1"><?= htmlspecialchars($order['customer_name']) ?></p>
                </div>
                <div class="col-6">
                    <small class="text-muted">Phone</small>
                    <p class="mb-1"><?= htmlspecialchars($order['phone'] ?? '—') ?></p>
                </div>
                <div class="col-6">
                    <small class="text-muted">Payment</small>
                    <p class="mb-1">
                        <?= htmlspecialchars($order['payment_method']) ?>
                        <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning text-dark' ?>">
                            <?= htmlspecialchars($order['payment_status']) ?>
                        </span>
                    </p>
                </div>
                <?php if ($order['notes']): ?>
                <div class="col-12 mt-2">
                    <small class="text-muted">Notes</small>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order items -->
    <div class="card mb-4">
        <div class="card-header fw-bold">Items Ordered</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td class="text-center"><?= (int)$item['quantity'] ?></td>
                        <td class="text-end">RM <?= number_format($item['price'], 2) ?></td>
                        <td class="text-end">RM <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold">RM <?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <a href="order_history.php" class="btn btn-secondary">← Back to History</a>

    <?php if (!empty($order['stripe_session_id'])): ?>
    <a href="view_receipt.php?session_id=<?= urlencode($order['stripe_session_id']) ?>"
       class="btn btn-outline-primary ms-2">View Receipt</a>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>