<?php
require_once 'db.php'; // Make sure this points to your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Depending on your db.php, you might use $conn or $pdo. 
    // Here is the MySQLi ($conn) version since you used it in your orders script:
    
    $stmt = $conn->prepare("DELETE FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>