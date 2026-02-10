<?php
session_start();
include 'db.php'; 

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

// Fetch current values
$result = mysqli_query($conn, "SELECT name, phone, password FROM user WHERE email = '$email' LIMIT 1");
if (!$result || mysqli_num_rows($result) === 0) {
    exit("user_not_found");
}
$current = mysqli_fetch_assoc($result);

// 1. Initialize arrays FIRST
$changes = [];
$set_parts = [];
$imagePath = '';

// 2. Handle Image Upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
    $uploadDir = 'uploads/'; 
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . '_' . basename($_FILES['profile_pic']['name']);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFilePath)) {
        $imagePath = $targetFilePath;
        $set_parts[] = "profilePic = '" . mysqli_real_escape_string($conn, $imagePath) . "'";
        $changes[] = 'profilePic';
    }
}

// 3. Decide what else changed
if ($name !== '' && $name !== $current['name']) {
    $changes[] = 'name';
    $set_parts[] = "name = '" . mysqli_real_escape_string($conn, $name) . "'";
}

if ($phone !== $current['phone']) {
    $changes[] = 'phone';
    $set_parts[] = "phone = '" . mysqli_real_escape_string($conn, $phone) . "'";
}

if (!empty($pass)) {
    $changes[] = 'password';
    $set_parts[] = "password = '" . mysqli_real_escape_string($conn, $pass) . "'";
}

// 4. Check if anything happened
if (empty($set_parts)) {
    exit("no_changes");
}

// 5. Run the query ONCE
$query = "UPDATE user SET " . implode(', ', $set_parts) . " WHERE email = '$email'";

if (mysqli_query($conn, $query)) {
    // Return: success | list,of,changes | new_image_path
    echo "success|" . implode(",", $changes) . "|" . $imagePath;
} else {
    echo "error|" . mysqli_error($conn);
}

mysqli_close($conn);
?>