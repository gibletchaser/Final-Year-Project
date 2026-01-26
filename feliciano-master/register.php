<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass  = $_POST['password'];

    if (!$name || !$pass) {
        echo "missing";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // CUSTOMER ONLY
    $role = "customer";

    $stmt = $conn->prepare(
        "INSERT INTO userss (name, email, phone, password, role)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
