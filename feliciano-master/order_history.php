<?php
// order-history.php

session_start();

// Protect the page
if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

try {
    // 1. Get current customer's name & phone FROM the customer table
    $stmt = $pdo->prepare("SELECT name, phone FROM customer WHERE id = ?");
    $stmt->execute([ $_SESSION['user']['id'] ]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        // This should never happen if session is valid — but safety check
        session_destroy();
        header("Location: login.php?error=account_not_found");
        exit;
    }

    $customer_name = $customer['name'];
    $phone         = $customer['phone'];

    // 2. Now fetch orders using the authoritative values from DB
   $stmt = $pdo->prepare("
    INSERT INTO orders 
    (customer_name, phone, items, total_amount, payment_method, stripe_session_id, notes, payment_status, created_at, updated_at, order_status, customer_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
   $stmt->execute([
    $customer_name,               // 1
    $phone,                       // 2
    $items,                       // 3  (your items string or json_encode($cart))
    $total_amount,                // 4
    $payment_method,              // 5   e.g. 'stripe', 'cash', 'online banking'
    $stripe_session_id ?? null,   // 6   can be null if not using Stripe
    $notes ?? '',                 // 7
    $payment_status,              // 8   e.g. 'paid', 'pending', 'failed'
    $order_status ?? 'pending',   // 9   e.g. 'pending', 'preparing', 'completed'
    $_SESSION['user']['id']       // 10  ← this goes last because customer_id is last in the query
]);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $orders = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History - Yob Yong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- your other styles -->
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">My Order History</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
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
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <td><?= date('d M Y • h:i A', strtotime($order['created_at'])) ?></td>
                        <td style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($order['items'])) ?></td>
                        <td>RM <?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <?= htmlspecialchars($order['payment_method']) ?><br>
                            <small><?= htmlspecialchars($order['payment_status']) ?></small>
                        </td>
                        <td>
                            <span class="badge 
                                <?php
                                    $s = strtolower($order['order_status'] ?? 'pending');
                                    if     ($s === 'completed')  echo 'bg-success';
                                    elseif ($s === 'cancelled')  echo 'bg-danger';
                                    elseif (in_array($s, ['preparing','processing'])) echo 'bg-primary';
                                    else echo 'bg-warning text-dark';
                                ?>">
                                <?= ucfirst($order['order_status'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
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