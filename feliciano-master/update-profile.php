<?php
session_start();
include 'db.php'; // your connection file

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("invalid_request");
}

$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$name  = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pass  = $_POST['password'] ?? '';

if (empty($email)) {
    exit("missing_email");
}

// Fetch current values from DB to compare
$result = mysqli_query($conn, "SELECT name, phone, password FROM user WHERE email = '$email' LIMIT 1");

if (!$result || mysqli_num_rows($result) === 0) {
    exit("user_not_found");
}

$current = mysqli_fetch_assoc($result);

// Decide what actually changed
$changes = [];

if ($name !== '' && $name !== $current['name']) {
    $changes[] = 'name';
}

if ($phone !== $current['phone']) {  // allow clearing phone
    $changes[] = 'phone';
}

$password_changed = !empty($pass);

if ($password_changed) {
    $changes[] = 'password';
}

// Nothing changed → early exit
if (empty($changes)) {
    exit("no_changes");
}

// Build query
$set_parts = [];
$params = [];

if (in_array('name', $changes)) {
    $set_parts[] = "name = '" . mysqli_real_escape_string($conn, $name) . "'";
}
if (in_array('phone', $changes)) {
    $set_parts[] = "phone = '" . mysqli_real_escape_string($conn, $phone) . "'";
}
if ($password_changed) {
    // TODO: in real project → hash the password!
    $set_parts[] = "password = '" . mysqli_real_escape_string($conn, $pass) . "'";
}

if (empty($set_parts)) {
    exit("no_changes"); // safety
}

$query = "UPDATE user SET " . implode(', ', $set_parts) . " WHERE email = '$email'";

if (mysqli_query($conn, $query)) {
    // Return what changed so JS can make nice message
    echo "success|" . implode(",", $changes);
} else {
    echo "error|" . mysqli_error($conn);
}

mysqli_close($conn);
?>