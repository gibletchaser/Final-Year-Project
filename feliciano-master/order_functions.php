<?php
function createOrder($pdo, $user_id, $cart_items, $total, $payment_id) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO orders (customer_id, total_amount, payment_id, order_status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([$user_id, $total, $payment_id]);
        $order_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, menu_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($cart_items as $item) {
            $stmt->execute([
                $order_id,
                $item['menu_id'],
                $item['quantity'],
                $item['price']
            ]);
        }

        $pdo->commit();
        return $order_id;

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function clearCart($pdo, $user_id) {
    if ($user_id) {
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$user_id]);
    } else {
        $session_id = session_id();
        $stmt = $pdo->prepare("DELETE FROM carts WHERE session_id = ?");
        $stmt->execute([$session_id]);
    }
}

function getCustomerOrders($pdo, $user_id, $limit = 10) {
    $limit = (int)$limit;
    $stmt  = $pdo->prepare("
        SELECT o.*,
               COUNT(oi.id)   AS item_count,
               o.order_status AS current_status
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.customer_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT " . $limit
    );
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderDetails($pdo, $order_id, $user_id = null) {
    $sql = "
        SELECT o.*,
               c.name  AS customer_name,
               c.email AS customer_email,
               o.order_status AS current_status
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        WHERE o.id = ?
    ";
    $params = [$order_id];

    if ($user_id) {
        $sql     .= " AND o.customer_id = ?";
        $params[] = $user_id;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getOrderItems($pdo, $order_id) {
    $stmt = $pdo->prepare("
        SELECT oi.*, mi.name, mi.image
        FROM order_items oi
        JOIN menu mi ON oi.menu_id = mi.id
        WHERE oi.order_id = ?
        ORDER BY oi.id
    ");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderStatusHistory($pdo, $order_id) {
    $stmt = $pdo->prepare("
        SELECT order_status AS status, updated_at AS created_at, notes
        FROM orders
        WHERE id = ?
    ");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateOrderStatus($pdo, $order_id, $status, $notes = null) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, notes = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $notes, $order_id]);

        if ($status === 'preparing') {
            $ready_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $stmt = $pdo->prepare("UPDATE orders SET estimated_ready_time = ? WHERE id = ?");
            $stmt->execute([$ready_time, $order_id]);
        }

        if ($status === 'ready') {
            $pickup_code = strtoupper(substr(md5($order_id . time()), 0, 6));
            $stmt = $pdo->prepare("UPDATE orders SET pickup_code = ? WHERE id = ?");
            $stmt->execute([$pickup_code, $order_id]);
        }

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Status update error: " . $e->getMessage());
        return false;
    }
}

function getOrderStatusDisplay($status) {
    $statuses = [
        'pending'   => ['text' => 'Pending',   'class' => 'warning',   'icon' => 'bi-hourglass'],
        'confirmed' => ['text' => 'Confirmed', 'class' => 'info',      'icon' => 'bi-check-circle'],
        'preparing' => ['text' => 'Preparing', 'class' => 'primary',   'icon' => 'bi-cup-hot'],
        'ready'     => ['text' => 'Ready',     'class' => 'success',   'icon' => 'bi-bell'],
        'completed' => ['text' => 'Completed', 'class' => 'secondary', 'icon' => 'bi-check2-all'],
        'cancelled' => ['text' => 'Cancelled', 'class' => 'danger',    'icon' => 'bi-x-circle'],
    ];
    return $statuses[$status] ?? ['text' => ucfirst($status), 'class' => 'dark', 'icon' => 'bi-question-circle'];
}

function getOrdersWithFilters($pdo, $status = 'all', $date_from = '', $date_to = '', $search = '') {
    $sql = "
        SELECT o.*,
               c.name       AS customer_name,
               c.email      AS customer_email,
               c.profilePic AS customer_pic,
               COUNT(oi.id) AS item_count,
               o.order_status AS current_status
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE 1=1
    ";
    $params = [];

    if ($status !== 'all') {
        $sql     .= " AND o.order_status = ?";
        $params[] = $status;
    }
    if ($date_from) {
        $sql     .= " AND DATE(o.created_at) >= ?";
        $params[] = $date_from;
    }
    if ($date_to) {
        $sql     .= " AND DATE(o.created_at) <= ?";
        $params[] = $date_to;
    }
    if ($search) {
        $sql .= " AND (
            o.id LIKE ? OR
            c.name LIKE ? OR
            c.email LIKE ? OR
            o.stripe_session_id LIKE ? OR
            o.pickup_code LIKE ?
        )";
        $s      = "%$search%";
        $params = array_merge($params, [$s, $s, $s, $s, $s]);
    }

    $sql .= " GROUP BY o.id ORDER BY o.created_at DESC LIMIT 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderForDashboard($pdo, $order_id) {
    $stmt = $pdo->prepare("
        SELECT o.*,
               c.name       AS customer_name,
               c.email      AS customer_email,
               c.profilePic AS customer_pic,
               o.order_status AS current_status
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        WHERE o.id = ?
    ");
    $stmt->execute([$order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>