<?php
session_start();
require_once 'db.php'; // Your DB connection

// 1. SECURITY: Only allow logged in users
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION['user_email'];

// 2. QUERY: Fetch only THIS customer's orders
// We use 'id' to create the "Order Code" and parse 'items' for the count
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY id DESC");
$stmt->execute([$customer_email]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return as JSON for the frontend
echo json_encode($orders);
?>