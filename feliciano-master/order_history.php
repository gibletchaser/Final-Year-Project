<?php
session_start();

// Protect the page — use 'user' session key set by login.php
if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$customer_id = (int)$_SESSION['user']['id'];
$orders      = [];
$error       = null;

try {
    $stmt = $conn->prepare("
        SELECT o.*, COUNT(oi.id) AS item_count
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        WHERE o.customer_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

} catch (Exception $e) {
    $error = "Could not load orders. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History - Yobyong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">My Order History</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">You haven't placed any orders yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total (RM)</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($order['order_code']) ?></strong></td>
                        <td><?= date('d M Y • h:i A', strtotime($order['created_at'])) ?></td>
                        <td><?= (int)$order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?></td>
                        <td>RM <?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <?= htmlspecialchars($order['payment_method']) ?><br>
                            <small class="text-muted"><?= htmlspecialchars($order['payment_status']) ?></small>
                        </td>
                        <td>
                            <?php
                                $s = strtolower($order['order_status'] ?? 'pending');
                                $badgeClass = match($s) {
                                    'completed'              => 'bg-success',
                                    'cancelled'              => 'bg-danger',
                                    'processing', 'ready'    => 'bg-primary',
                                    default                  => 'bg-warning text-dark'
                                };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars(ucfirst($order['order_status'] ?? 'Pending')) ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-details.php?id=<?= (int)$order['id'] ?>"
                               class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>