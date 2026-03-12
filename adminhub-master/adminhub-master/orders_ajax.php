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
    $status = strtolower(trim($_GET['status'] ?? 'all'));
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
        $where[]  = '(o.customer_name LIKE ? OR o.id LIKE ?)';
        $params[] = $like;
        $params[] = $like;
        $types   .= 'ss';
    }

    $sql = "SELECT o.*, COUNT(oi.id) AS item_count
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

    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    $stmt2 = $conn->prepare(
        "SELECT oi.quantity AS qty, oi.price, COALESCE(oi.name, m.name) AS name, m.image
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
    $status = strtolower(trim($_POST['status'] ?? ''));

    $allowed = ['pending', 'processing', 'ready', 'completed', 'cancelled'];
    if (!in_array($status, $allowed)) {
        echo json_encode(['error' => 'Invalid status: ' . $status]);
        exit;
    }

    // Simple update — only touches order_status column which we know exists
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('si', $status, $id);
    $result = $stmt->execute();

    if (!$result) {
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
        exit;
    }

    echo json_encode(['success' => true, 'status' => $status]);
    exit;
}

// ── CANCEL ORDER ──────────────────────────────────────────────
if ($action === 'cancel') {
    $id = intval($_POST['id'] ?? 0);

    $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled' WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

// ── EXPORT SALES REPORT (CSV) ─────────────────────────────────
if ($action === 'export_report') {
    $from   = trim($_GET['from']   ?? '');
    $to     = trim($_GET['to']     ?? '');
    $status = strtolower(trim($_GET['status'] ?? 'all'));

    // Validate dates
    $dateOk = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to);
    if (!$dateOk) {
        echo json_encode(['error' => 'Invalid date range']);
        exit;
    }

    $where  = ['DATE(o.created_at) BETWEEN ? AND ?'];
    $params = [$from, $to];
    $types  = 'ss';

    $allowed = ['pending','processing','ready','completed','cancelled'];
    if ($status !== 'all' && in_array($status, $allowed)) {
        $where[]  = 'o.order_status = ?';
        $params[] = $status;
        $types   .= 's';
    }

    $sql = "SELECT o.id, o.order_code, o.customer_name, o.order_status,
                   o.total_amount, o.created_at,
                   GROUP_CONCAT(CONCAT(COALESCE(oi.name, m.name), ' x', oi.quantity) SEPARATOR ' | ') AS items
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            LEFT JOIN menu m ON m.id = oi.menu_id
            WHERE " . implode(' AND ', $where) . "
            GROUP BY o.id
            ORDER BY o.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Compute summary
    $totalRows    = count($rows);
    $totalRevenue = array_sum(array_column(
        array_filter($rows, fn($r) => $r['order_status'] === 'completed'),
        'total_amount'
    ));

    // Stream CSV
    $filename = 'sales_report_' . $from . '_to_' . $to . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');

    // Report header block
    fputcsv($out, ['YOBYONG SALES REPORT']);
    fputcsv($out, ['Generated', date('Y-m-d H:i:s')]);
    fputcsv($out, ['Period', $from . ' to ' . $to]);
    fputcsv($out, ['Status Filter', $status === 'all' ? 'All Statuses' : ucfirst($status)]);
    fputcsv($out, ['Total Orders', $totalRows]);
    fputcsv($out, ['Total Revenue (Completed)', 'RM ' . number_format($totalRevenue, 2)]);
    fputcsv($out, []); // blank row

    // Column headers
    fputcsv($out, ['Order ID', 'Order Code', 'Customer Name', 'Status', 'Total (RM)', 'Date & Time', 'Items']);

    // Rows
    foreach ($rows as $row) {
        fputcsv($out, [
            $row['id'],
            $row['order_code'],
            $row['customer_name'],
            ucfirst($row['order_status']),
            number_format($row['total_amount'], 2),
            $row['created_at'],
            $row['items'] ?? '',
        ]);
    }

    fclose($out);
    exit;
}

echo json_encode(['error' => 'Unknown action']);