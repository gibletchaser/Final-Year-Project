<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include 'db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── FETCH ORDERS LIST ─────────────────────────────────────────
if ($action === 'fetch') {
    $status = $_GET['status'] ?? 'all';
    $search = trim($_GET['search'] ?? '');

    $where  = [];
    $params = [];
    $types  = '';

    if ($status !== 'all') {
        $where[]  = 'o.order_status = ?';
        $params[] = $status;
        $types   .= 's';
    }

    if ($search !== '') {
        $like     = '%' . $search . '%';
        $where[]  = '(o.order_code LIKE ? OR o.customer_name LIKE ?)';
        $params[] = $like;
        $params[] = $like;
        $types   .= 'ss';
    }

    $sql = "SELECT o.*,
                   COUNT(oi.id) AS item_count
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id";

    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' GROUP BY o.id ORDER BY o.created_at DESC';

    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode($rows);
    exit;
}

// ── FETCH SINGLE ORDER DETAIL ─────────────────────────────────
if ($action === 'detail') {
    $id = intval($_GET['id'] ?? 0);

    $stmt = $conn->prepare(
        "SELECT o.*
         FROM orders o
         WHERE o.id = ?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // Fetch items separately
    $stmt2 = $conn->prepare(
        "SELECT oi.*, m.image
         FROM order_items oi
         LEFT JOIN menu m ON m.id = oi.menu_id
         WHERE oi.order_id = ?"
    );
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $order['items'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode($order);
    exit;
}

// ── UPDATE ORDER STATUS ───────────────────────────────────────
if ($action === 'update_status') {
    $id     = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $allowed = ['Pending', 'Processing', 'Ready', 'Completed', 'Cancelled'];
    if (!in_array($status, $allowed)) {
        echo json_encode(['error' => 'Invalid status']);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE orders SET order_status = ? WHERE id = ?"
    );
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();

    echo json_encode(['success' => true, 'status' => $status]);
    exit;
}

// ── CANCEL ORDER ──────────────────────────────────────────────
if ($action === 'cancel') {
    $id = intval($_POST['id'] ?? 0);

    $stmt = $conn->prepare(
        "UPDATE orders SET order_status = 'Cancelled' WHERE id = ?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);