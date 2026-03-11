<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    // Start building the update query
    $updates = [];
    $params = [];
    
    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileInfo = pathinfo($_FILES['profile_pic']['name']);
        $extension = strtolower($fileInfo['extension']);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($extension, $allowedExtensions)) {
            // Generate unique filename
            $newFileName = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                $updates[] = "profilePic = ?";
                $params[] = $uploadPath;
            }
        }
    }
    
    // Add other updates
    if (!empty($name)) {
        $updates[] = "name = ?";
        $params[] = $name;
    }
    
    if (!empty($phone)) {
        $updates[] = "phone = ?";
        $params[] = $phone;
    }
    
    if (!empty($_POST['password'])) {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updates[] = "password = ?";
        $params[] = $hashedPassword;
    }
    
    // Execute update if there are changes
    if (!empty($updates)) {
        $params[] = $email; // Add email for WHERE clause
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE email = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        // Return success with updated profile pic path if uploaded
        $profilePicPath = isset($uploadPath) ? $uploadPath : '';
        echo "success|Profile updated|" . $profilePicPath;
    } else {
        echo "error|No changes to update";
    }
}
?>
