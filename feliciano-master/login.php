<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        echo "missing";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($pass, $user['password'])) {

            // ✅ USER IS LOGGED IN
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            echo "success";
            exit;
        }
    }

    echo "invalid";
}
