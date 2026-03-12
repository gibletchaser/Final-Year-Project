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
// ─────────────────────────────────────────────────────────────────────────────

$orders = [];
$error  = null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=yobyong;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $orders = getCustomerOrders($pdo, $user_id, 20);
} catch (PDOException $e) {
    $error = "Database connection failed: " . $e->getMessage();
} catch (Exception $e) {
    $error = "Could not load orders: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History - Yobyong</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Modern, clean Instagrammable background */
        body { 
            background-image: url('images/bg76.jpg'); 

            background-color: #1c181884;
            background-blend-mode: multiply;

            font-family: 'Helvetica Neue', Arial, sans-serif;
        } 
        
        .order-list-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 15px;
        }

        /* Aesthetic Back Button */
        .back-btn {
            color: #000;
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
        .back-btn:hover {
            color: #C0A57B; /* Turns tan on hover */
        }

        /* GRID LAYOUT: This forces the cards into squares side-by-side */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        /* Square, Minimalist Cards */
        .order-card {
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-top: 4px solid #C0A57B; /* The tan color */
            border-radius: 0px; 
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            aspect-ratio: 1 / 1; /* This CSS trick makes the box a perfect square */
            color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(192, 165, 123, 0.15); /* Soft tan shadow on hover */
        }
        
        .order-info {
            flex-grow: 1; /* Pushes buttons to the bottom */
        }
        
        .text-accent { 
            color: #000; 
            font-weight: 800; 
            letter-spacing: 0.5px;
            font-size: 1.2rem;
        }
        
        /* Badges using your specific tan color */
        .aesthetic-badge {
            background-color: #C0A57B;
            color: #fff;
            border-radius: 0px; 
            padding: 4px 10px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 1rem;
        }

        /* Sleek Buttons */
        .custom-btn-primary {
            background-color: #C0A57B; /* Tan color */
            color: #fff;
            border: 1px solid #C0A57B;
            border-radius: 0px; 
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }
        .custom-btn-primary:hover { 
            background: #a88d61; 
            border-color: #a88d61;
            color: #fff; 
        }
        
        .custom-btn-secondary {
            border: 1px solid #eaeaea;
            color: #666;
            background: transparent;
            border-radius: 0px;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }
        .custom-btn-secondary:hover { 
            border-color: #000;
            color: #000; 
        }
        
        .small-label {
            color: #999;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            font-weight: 500;
        }

        .data-text {
            color: #000;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="order-list-container">
    
    <a href="javascript:history.back()" class="back-btn" style="color: #fafafa;">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 style="color: #C0A57B; font-weight: 800; letter-spacing: -0.5px; margin: 0;">
            Order History.
        </h2>
        
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger shadow-sm border-0 rounded-0"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($orders) && !$error): ?>
        <div class="alert text-center py-5 border-0" style="background: #fff; color: #888; border: 1px dashed #ddd !important; border-radius: 0;">
            <i class="bi bi-bag-x fs-1 d-block mb-3" style="color: #ccc;"></i>
            You have no orders yet.
        </div>
    <?php elseif (!empty($orders)): ?>
        
        <div class="orders-grid">
            <?php foreach ($orders as $order):
                $status = $order['current_status'] ?? $order['order_status'] ?? 'pending';
                
                // Format the date
                $order_date = isset($order['created_at']) ? date('M d, Y', strtotime($order['created_at'])) : 'Recently';
                
                // Count the items
                $items_array = json_decode($order['items'] ?? '[]', true);
                $item_count = is_array($items_array) ? count($items_array) : 0;
                
                // Format ID to use YY- prefix
                $formatted_id = sprintf("#YY-%05d", $order['id']);
            ?>
            
            <div class="order-card">
                <div class="order-info">
                    <span class="aesthetic-badge">
                        <?= htmlspecialchars($status) ?>
                    </span>
                    <h5 class="text-accent mb-4"><?php echo $formatted_id; ?></h5>
                    
                    <div class="small-label">Date</div>
                    <p class="data-text"><?php echo $order_date; ?></p>
                    
                    <div class="small-label">Items</div>
                    <p class="data-text"><?php echo $item_count; ?> items</p>
                    
                    <div class="small-label">Total</div>
                    <p class="data-text" style="font-size: 1.2rem;">RM <?php echo number_format($order['total_amount'] ?? 0, 2); ?></p>
                </div>

                <div class="order-actions d-flex flex-column gap-2 mt-auto">
                    <a href="view_order_status.php?id=<?php echo $order['id']; ?>" class="btn custom-btn-primary btn-sm w-100 py-2">
                        Track Order
                    </a>
                    
                    <a href="view_receipt.php?session_id=<?php echo urlencode($order['stripe_session_id'] ?? ''); ?>" class="btn custom-btn-secondary btn-sm w-100 py-2">
                        View Receipt
                    </a>
                </div>
            </div>
            
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>