<?php
session_start();
require "db.php"; // Ensure this points to your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    // 1. Search for the user. Use 'user' or 'users' based on your table name.
    $stmt = $conn->prepare("SELECT name, email, phone, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // 2. Check password (using plain text as per your register logic)
        if ($pass === $user['password']) {
            // 3. SEND THE DATA PACKAGE
            echo json_encode([
                "status" => "success",
                "user"   => [
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "phone" => $user['phone'],
                    "password" => $user['password']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Wrong password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
}
?>