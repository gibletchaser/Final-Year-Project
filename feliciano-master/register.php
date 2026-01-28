<?php
session_start(); // Start the session so the profile works
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    
    // We set a placeholder name so the Profile isn't "undefined"
    $name  = "Valued Member"; 

    if (empty($email) || empty($pass)) {
        echo "missing";
        exit;
    }

    // CHECK: Using 'user' (singular) to match your actual database table
    $stmt = $conn->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $pass);

        if ($stmt->execute()) {
            // ✅ SUCCESS: Log the user in immediately
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name; 
            echo "success"; 
        } else {
            echo "Error: Registration failed. Email might already exist.";
        }
    } else {
        // This catch helps you see if the table name is STILL wrong
        echo "Database Error: Table 'user' not found in db.php.";
    }
}
?>