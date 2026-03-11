<?php
session_start();
require 'db.php';
require 'order_functions.php';

<<<<<<< Updated upstream
=======
<<<<<<< HEAD
// 1. Database Configuration
$host = 'localhost';
$dbname = 'yobyong'; 
$username = 'root';
$password = ''; 

if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$customer_id = (int)$_SESSION['user']['id'];
$orders      = [];
$error       = null;

$customer_id = (int)$_SESSION['user']['id'];
$orders      = [];
$error       = null;

try {
<<<<<<< Updated upstream
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
=======
<<<<<<< HEAD
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// 2. Get User ID
$user_id = $_SESSION['user_id'] ?? 2; 

// 3. Fetch Orders
$orders = getCustomerOrders($pdo, $user_id, 20); 
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
=======
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
>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History - Yobyong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
>>>>>>> bd33f361939d2c1b9b18746cecfd216e8db38528

<style>
    body { background-color: #f4f6f9; } /* Light background so you can see things clearly */
    
    .order-list-container { 
        max-width: 900px; 
        margin: 40px auto; 
        padding: 0 15px; 
    }
    
    .order-card {
        background-color: #2c241b; /* Dark coffee brown */
        border: 1px solid #4a3f35;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #f8f9fa;
    }
    
    .order-info p { margin-bottom: 0.2rem; font-size: 0.9rem; color: #d1d1d1; }
    .text-gold { color: #d4af37; font-weight: bold; }
    
    /* Fixed Button Colors so they are visible immediately! */
    .custom-btn-gold {
        border: 1px solid #d4af37;
        color: #d4af37; 
        background: transparent;
        border-radius: 5px;
    }
    .custom-btn-gold:hover { background: #d4af37; color: #000; }
    
    .custom-btn-light {
        border: 1px solid #e9ecef;
        color: #e9ecef; /* Bright white/gray text */
        background: transparent;
        border-radius: 5px;
    }
    .custom-btn-light:hover { background: #e9ecef; color: #000; }
    
    .badge { padding: 6px 10px; }
</style>

<<<<<<< HEAD
<div class="order-list-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #2c241b; font-weight: bold;">
            <i class="bi bi-clock-history me-2"></i>Order History
        </h2>
        <a href="track_order.php" class="btn" style="background-color: #d4af37; color: #000; font-weight: bold; border-radius: 8px;">
            <i class="bi bi-truck me-1"></i> Track Order
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-dark text-center py-5" style="background: #2c241b; color: #ccc;">
            You have no orders yet. 
        </div>
    <?php else: ?>
        
        <?php foreach ($orders as $order): 
            $status = $order['current_status'] ?? 'pending';
            $statusDisplay = getOrderStatusDisplay($status); 
            $order_date = isset($order['created_at']) ? date('M d, Y h:i A', strtotime($order['created_at'])) : 'Recently';
            
            // FIX: Count the items by decoding the JSON array from your database
            $items_array = json_decode($order['items'], true);
            $item_count = is_array($items_array) ? count($items_array) : 0;
            
            // Format ID
            $formatted_id = sprintf("#KTB-%05d", $order['id']); 
        ?>
        
        <div class="order-card shadow-sm">
            <div class="order-info w-75">
                <div class="d-flex align-items-center mb-2">
                    <h5 class="text-gold mb-0 me-3"><?php echo $formatted_id; ?></h5>
                    <span class="badge bg-warning text-dark rounded-pill text-uppercase" style="font-size: 0.75rem;">
                        <i class="bi bi-hourglass-split me-1"></i> PENDING
                    </span>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="color: #888 !important;">Order Date</small>
                        <p><?php echo $order_date; ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="color: #888 !important;">Items</small>
                        <p><?php echo $item_count; ?> items</p> </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="color: #888 !important;">Total</small>
                        <p class="fw-bold text-white">RM <?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="order-actions w-25 text-end d-flex flex-column gap-2">
                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn custom-btn-gold btn-sm w-100 py-2">
                    <i class="bi bi-eye me-1"></i> View Details
                </a>
                
                <a href="view_receipt.php?id=<?php echo $order['id']; ?>" class="btn custom-btn-light btn-sm w-100 py-2">
                    <i class="bi bi-receipt me-1"></i> View Receipt
                </a>
            </div>
=======
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
>>>>>>> bd33f361939d2c1b9b18746cecfd216e8db38528
        </div>
        
        <?php endforeach; ?>
    <?php endif; ?>
</div>