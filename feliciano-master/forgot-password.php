<?php
require "db.php"; // Your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newPass = $_POST['new_password'];

    // Check if user exists
    $check = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update the password
        $update = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $update->bind_param("ss", $newPass, $email);
        
        if ($update->execute()) {
            echo "<script>alert('Password updated! Please Sign In.'); window.location.href='sign in.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found in our records.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #f8f9fa; padding-top: 50px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 card p-4 shadow">
                <h2 class="text-center mb-4">Reset Password</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Your Registered Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="background: #c4a47c; border: none;">Update Password</button>
                    <div class="text-center mt-3">
                        <a href="sign in.php" style="color: #c4a47c;">Back to Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>