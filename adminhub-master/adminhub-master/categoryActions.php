<?php
/**
 * categoryActions.php
 * Handles AJAX requests for category management.
 * Actions: list | add | delete
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

include 'db.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {

  /* ── LIST all categories ── */
  case 'list':
    $result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
    $cats = [];
    while ($row = $result->fetch_assoc()) {
      $cats[] = $row;
    }
    echo json_encode($cats);
    break;

  /* ── ADD a new category ── */
  case 'add':
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
      echo json_encode(['success' => false, 'message' => 'Category name cannot be empty.']);
      break;
    }

    // Prevent duplicates
    $check = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $check->bind_param('s', $name);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
      echo json_encode(['success' => false, 'message' => 'Category already exists.']);
      $check->close();
      break;
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param('s', $name);

    if ($stmt->execute()) {
      echo json_encode([
        'success' => true,
        'id'      => $conn->insert_id,
        'name'    => $name
      ]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    $stmt->close();
    break;

  /* ── DELETE a category ── */
  case 'delete':
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid category ID.']);
      break;
    }

    // Uncategorise menu items that belong to this category
    $uncat = $conn->prepare("UPDATE menus SET category_id = NULL WHERE category_id = ?");
    $uncat->bind_param('i', $id);
    $uncat->execute();
    $uncat->close();

    $del = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $del->bind_param('i', $id);

    if ($del->execute()) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    $del->close();
    break;

  default:
    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
}

$conn->close();
?>