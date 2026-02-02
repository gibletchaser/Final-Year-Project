<?php
session_start();
require "db.php"; // Ensure this points to your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    // 1. Search for the user. (Added 'is_verified' to the SELECT)
    $stmt = $conn->prepare("SELECT name, email, phone, password, is_verified FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // 2. Check password (using plain text as per your register logic)
        if ($pass === $user['password']) {
            
            // --- ADDED VERIFICATION CHECK HERE ---
            if ($user['is_verified'] == 0) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Please verify your email before logging in. Check your email for the verification code."
                ]);
                exit();
            }
            // -------------------------------------

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