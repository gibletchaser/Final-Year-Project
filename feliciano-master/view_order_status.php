<?php
session_start();
require_once 'db.php';
require_once 'order_functions.php';

// ─── Resolve user_id ──────────────────────────────────────────────────────────
$user_id = null;

if (!empty($_SESSION['user']['id'])) {
    $user_id = (int)$_SESSION['user']['id'];
} elseif (!empty($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
} elseif (!empty($_SESSION['user_email'])) {
    $email = $conn->real_escape_string($_SESSION['user_email']);
    $res   = $conn->query("SELECT id FROM customers WHERE email = '$email' LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $user_id = (int)$row['id'];
    }
}

if (!$user_id) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: order_history.php");
    exit;
}

$order_id = (int)$_GET['id'];
$order    = null;
$items    = [];
$error    = null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=yobyong;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $error = "Order not found or you don't have permission to view it.";
    } else {
        $items = getOrderItems($pdo, $order_id);
    }

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// ─── Status pipeline ──────────────────────────────────────────────────────────
$pipeline = [
    'pending'   => ['label' => 'Order Placed',     'icon' => 'bi-bag-check',   'desc' => 'We have received your order.'],
    'confirmed' => ['label' => 'Order Confirmed',  'icon' => 'bi-check-circle','desc' => 'Your order has been confirmed.'],
    'preparing' => ['label' => 'Preparing',         'icon' => 'bi-cup-hot',     'desc' => 'Our kitchen is preparing your order.'],
    'ready'     => ['label' => 'Ready for Pickup',  'icon' => 'bi-bell',        'desc' => 'Your order is ready! Come pick it up.'],
    'completed' => ['label' => 'Completed',         'icon' => 'bi-check2-all',  'desc' => 'Order completed. Enjoy your meal!'],
];

// FIX: Always lowercase so it matches pipeline keys regardless of how admin saved it
$current_status = strtolower($order['order_status'] ?? 'pending');
$is_cancelled   = $current_status === 'cancelled';
$pipeline_keys  = array_keys($pipeline);
$current_index  = array_search($current_status, $pipeline_keys);
if ($current_index === false) $current_index = 0;

$formatted_id = $order ? sprintf("#YY-%05d", $order['id']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order <?= $formatted_id ?> - Yobyong</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/bg76.jpg');
            background-color: #1c181884;
            background-blend-mode: multiply;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
        }
        .track-container {
            max-width: 720px;
            margin: 40px auto;
            padding: 0 15px 60px;
        }
        .back-btn {
            color: #fafafa;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }
        .back-btn:hover { color: #C0A57B; }
        .order-header-card {
            background: #fff;
            border-top: 4px solid #C0A57B;
            border-radius: 0;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .order-id {
            font-size: 1.4rem;
            font-weight: 800;
            color: #000;
            letter-spacing: 0.5px;
        }
        .order-meta {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-pill {
            background-color: #C0A57B;
            color: #fff;
            border-radius: 0;
            padding: 5px 14px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .status-pill.cancelled { background-color: #dc3545; }
        .tracker-card {
            background: #fff;
            border-radius: 0;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .tracker-card h6 {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 2rem;
        }
        .steps-wrapper {
            position: relative;
            padding-left: 20px;
        }
        .steps-wrapper::before {
            content: '';
            position: absolute;
            left: 34px;
            top: 20px;
            bottom: 20px;
            width: 2px;
            background: #eaeaea;
            z-index: 0;
        }
        .step-row {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        .step-row:last-child { margin-bottom: 0; }
        .step-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            border: 2px solid #eaeaea;
            background: #fff;
            transition: all 0.3s ease;
        }
        .step-row.done .step-icon-wrap {
            background: #C0A57B;
            border-color: #C0A57B;
            color: #fff;
        }
        .step-row.active .step-icon-wrap {
            background: #fff;
            border-color: #C0A57B;
            color: #C0A57B;
            animation: pulse-ring 1.8s ease-out infinite;
        }
        .step-row.active .step-desc { color: #C0A57B; font-weight: 600; }
        .step-row.future .step-icon-wrap {
            background: #f8f8f8;
            border-color: #eaeaea;
            color: #ccc;
        }
        .step-row.future .step-label { color: #bbb; }
        .step-content { padding-top: 8px; }
        .step-label { font-weight: 700; font-size: 0.95rem; color: #000; margin-bottom: 2px; }
        .step-desc  { font-size: 0.8rem; color: #999; }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(192,165,123,0.4); }
            70%  { box-shadow: 0 0 0 10px rgba(192,165,123,0); }
            100% { box-shadow: 0 0 0 0 rgba(192,165,123,0); }
        }
        .cancelled-banner {
            background: #fff5f5;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #dc3545;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .cancelled-banner i { font-size: 1.5rem; color: #dc3545; }
        .cancelled-banner p { margin: 0; font-size: 0.9rem; color: #721c24; font-weight: 600; }
        .pickup-card {
            background: #000;
            border-radius: 0;
            padding: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .pickup-label {
            color: #C0A57B;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .pickup-code { color: #fff; font-size: 2.8rem; font-weight: 800; letter-spacing: 8px; }
        .pickup-hint { color: #666; font-size: 0.78rem; margin-top: 0.5rem; }
        .items-card {
            background: #fff;
            border-radius: 0;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .items-card-header {
            background: #fafafa;
            border-bottom: 1px solid #eaeaea;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #999;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 1.5rem;
            border-bottom: 1px solid #f5f5f5;
        }
        .item-row:last-child { border-bottom: none; }
        .item-name  { font-weight: 600; font-size: 0.9rem; color: #000; }
        .item-qty   { font-size: 0.8rem; color: #999; margin-top: 2px; }
        .item-price { font-weight: 700; font-size: 0.9rem; color: #000; }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background: #fafafa;
            border-top: 2px solid #eaeaea;
        }
        .total-row span          { font-weight: 800; font-size: 1rem; }
        .total-row .total-amount { color: #C0A57B; font-size: 1.1rem; }
        .refresh-notice {
            text-align: center;
            font-size: 0.75rem;
            color: #aaa;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }
        .refresh-notice span { color: #C0A57B; font-weight: 700; }
    </style>
</head>
<body>

<div class="track-container">

    <a href="order_history.php" class="back-btn">
        <i class="bi bi-arrow-left me-2"></i> Back to Orders
    </a>

    <?php if ($error): ?>
        <div class="alert alert-danger rounded-0 border-0"><?= htmlspecialchars($error) ?></div>

    <?php elseif ($order): ?>

        <!-- ── Order Header ── -->
        <div class="order-header-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="order-id"><?= $formatted_id ?></div>
                    <div class="order-meta mt-1">
                        <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                        &nbsp;·&nbsp;
                        <?= htmlspecialchars($order['customer_name'] ?? '') ?>
                    </div>
                </div>
                <span class="status-pill <?= $is_cancelled ? 'cancelled' : '' ?>">
                    <?= ucfirst($current_status) ?>
                </span>
            </div>
        </div>

        <?php if ($is_cancelled): ?>

        <!-- ── Cancelled Banner ── -->
        <div class="cancelled-banner">
            <i class="bi bi-x-circle-fill"></i>
            <p>This order has been cancelled. Please contact us if you have any questions.</p>
        </div>

        <?php else: ?>

        <!-- ── Pickup Code (only when ready) ── -->
        <?php if ($current_status === 'ready' && !empty($order['pickup_code'])): ?>
        <div class="pickup-card">
            <div class="pickup-label">Your Pickup Code</div>
            <div class="pickup-code"><?= htmlspecialchars($order['pickup_code']) ?></div>
            <div class="pickup-hint">Show this code at the counter to collect your order.</div>
        </div>
        <?php endif; ?>

        <!-- ── Progress Tracker ── -->
        <div class="tracker-card">
            <h6>Order Progress</h6>
            <div class="steps-wrapper">
                <?php foreach ($pipeline as $key => $step):
                    $step_index = array_search($key, $pipeline_keys);
                    if ($step_index < $current_index)       $state = 'done';
                    elseif ($step_index === $current_index) $state = 'active';
                    else                                    $state = 'future';
                ?>
                <div class="step-row <?= $state ?>">
                    <div class="step-icon-wrap">
                        <?php if ($state === 'done'): ?>
                            <i class="bi bi-check-lg"></i>
                        <?php else: ?>
                            <i class="bi <?= $step['icon'] ?>"></i>
                        <?php endif; ?>
                    </div>
                    <div class="step-content">
                        <div class="step-label"><?= $step['label'] ?></div>
                        <div class="step-desc">
                            <?php if ($state === 'active'): ?>
                                <?= $step['desc'] ?>
                            <?php elseif ($state === 'done'): ?>
                                Completed
                            <?php else: ?>
                                Waiting...
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($current_status === 'preparing' && !empty($order['estimated_ready_time'])): ?>
            <div class="mt-4 pt-3 border-top text-center">
                <div class="order-meta">Estimated Ready Time</div>
                <div style="font-size:1.1rem; font-weight:800; color:#C0A57B; margin-top:4px;">
                    <?= date('h:i A', strtotime($order['estimated_ready_time'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; // end not-cancelled ?>

        <!-- ── Order Items Summary ── -->
        <div class="items-card">
            <div class="items-card-header">Items in this Order</div>
            <?php if (empty($items)): ?>
                <div class="item-row">
                    <span class="item-name text-muted">No items found.</span>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                <div class="item-row">
                    <div>
                        <div class="item-name"><?= htmlspecialchars($item['name'] ?? '—') ?></div>
                        <div class="item-qty">x<?= (int)$item['quantity'] ?></div>
                    </div>
                    <div class="item-price">
                        RM <?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="total-row">
                <span>Total</span>
                <span class="total-amount">RM <?= number_format($order['total_amount'], 2) ?></span>
            </div>
        </div>

        <?php if (!empty($order['stripe_session_id'])): ?>
        <div class="text-center mt-3">
            <a href="view_receipt.php?session_id=<?= urlencode($order['stripe_session_id']) ?>"
               style="color:#C0A57B; font-size:0.8rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; text-decoration:none;">
                <i class="bi bi-receipt me-1"></i> View Receipt
            </a>
        </div>
        <?php endif; ?>

        <?php if (!in_array($current_status, ['completed', 'cancelled'])): ?>
        <div class="refresh-notice mt-4">
            Page refreshes automatically every <span>30 seconds</span>
        </div>
        <script>
            setTimeout(function() { window.location.reload(); }, 30000);
            let seconds = 30;
            const span  = document.querySelector('.refresh-notice span');
            setInterval(function() {
                seconds--;
                if (seconds <= 0) seconds = 30;
                span.textContent = seconds + ' seconds';
            }, 1000);
        </script>
        <?php endif; ?>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>