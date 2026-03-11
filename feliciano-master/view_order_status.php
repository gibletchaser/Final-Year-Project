<?php
session_start();
require 'db.php';
require 'order_functions.php';

if (!hasPermission('view_dashboard')) {
    header('Location: index.php');
    exit();
}

// Get only active orders
$active_orders = getActiveOrders($pdo); // Assuming this fetches orders NOT cancelled/completed

$columns = [
    'pending' => [],
    'preparing' => [],
    'ready' => []
];

// Sort orders into columns
foreach ($active_orders as $order) {
    if (array_key_exists($order['current_status'], $columns)) {
        $columns[$order['current_status']][] = $order;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="30">
    <title>Live Order Tracking</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .kanban-col { min-height: 80vh; background-color: #f8f9fa; border-radius: 8px; padding: 15px; }
        .order-card-kds { background: white; border-left: 4px solid #ccc; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .order-card-kds.pending { border-left-color: #ffc107; }
        .order-card-kds.preparing { border-left-color: #0dcaf0; }
        .order-card-kds.ready { border-left-color: #198754; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4 mb-4">
        <a class="navbar-brand fw-bold" href="dashboard.php">Kitchen Display System</a>
        <span class="text-white">Last updated: <?php echo date('H:i:s'); ?></span>
    </nav>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="kanban-col">
                    <h4 class="mb-3 text-warning border-bottom pb-2"><i class="bi bi-hourglass"></i> Pending (<?php echo count($columns['pending']); ?>)</h4>
                    <?php foreach ($columns['pending'] as $order): ?>
                        <div class="order-card-kds pending p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                <small class="text-muted"><?php echo date('H:i', strtotime($order['created_at'])); ?></small>
                            </div>
                            <form action="orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="preparing">
                                <button type="submit" name="update_status" class="btn btn-sm btn-info w-100 mt-2">Start Preparing</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="kanban-col">
                    <h4 class="mb-3 text-info border-bottom pb-2"><i class="bi bi-fire"></i> Preparing (<?php echo count($columns['preparing']); ?>)</h4>
                    <?php foreach ($columns['preparing'] as $order): ?>
                        <div class="order-card-kds preparing p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                            </div>
                            <form action="orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" name="update_status" class="btn btn-sm btn-success w-100 mt-2">Mark Ready</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="kanban-col">
                    <h4 class="mb-3 text-success border-bottom pb-2"><i class="bi bi-bell"></i> Ready for Pickup (<?php echo count($columns['ready']); ?>)</h4>
                    <?php foreach ($columns['ready'] as $order): ?>
                        <div class="order-card-kds ready p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                <span class="badge bg-dark"><?php echo $order['pickup_code']; ?></span>
                            </div>
                            <form action="orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-secondary w-100 mt-2">Complete Order</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>