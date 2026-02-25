<?php
include 'db.php';

$categoryId = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : null;
$search     = trim($_GET['search'] ?? '');

// Build query dynamically
$sql    = "SELECT m.id, m.name, m.price, m.image, c.name AS category_name
           FROM menu m
           LEFT JOIN categories c ON m.category_id = c.id
           WHERE 1=1";
$params = [];
$types  = '';

if ($categoryId !== null) {
    $sql     .= " AND m.category_id = ?";
    $params[] = $categoryId;
    $types   .= 'i';
}

if ($search !== '') {
    $like     = '%' . $search . '%';
    $sql     .= " AND m.name LIKE ?";
    $params[] = $like;
    $types   .= 's';
}

$sql .= " ORDER BY m.name ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$menus  = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

header('Content-Type: application/json');
echo json_encode($menus);
?>