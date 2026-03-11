<?php
session_start(); // Start session

// Database connection (reuse from menu.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $itemId = (int)($_POST['itemId'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (!$itemId) {
        echo json_encode(['success' => false, 'message' => 'Invalid item']);
        exit;
    }

    // Fetch item details from DB
    $stmt = $conn->prepare("SELECT name, price, image FROM menu WHERE id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
        exit;
    }
    $item = $result->fetch_assoc();
    $stmt->close();

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === 'add') {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemId] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $quantity
            ];
        }
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
    } elseif ($action === 'remove') {
        if (isset($_SESSION['cart'][$itemId])) {
            unset($_SESSION['cart'][$itemId]);
        }
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
    } elseif ($action === 'update') {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]['quantity'] = $quantity;
        }
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>