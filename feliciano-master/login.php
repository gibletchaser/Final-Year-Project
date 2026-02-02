<?php
session_start();
require "db.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT name, email, phone, password, is_verified FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ($pass === $user['password']) {
            
            if ($user['is_verified'] == 0) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Please verify your email before logging in. Check your inbox for the verification code."
                ]);
                exit();
            }

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
            // Wrong password case
            echo json_encode(["status" => "error", "message" => "Invalid Email or Password"]);
        }
    } else {
        // Email not found case - now shows the same message as wrong password
        echo json_encode(["status" => "error", "message" => "Invalid Email or Password"]);
    }
}
?>