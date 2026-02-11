<?php
session_start();
require "db.php";

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

// Validate token
if ($token) {
    $sql = "SELECT name, reset_token_expires FROM user WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $error = "System error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = 'Invalid or expired reset link.';
        } elseif (strtotime($user['reset_token_expires']) < time()) {
            $error = 'Reset link has expired. Please request a new one.';
        }
    }
} else {
    $error = 'No reset token provided.';
}

// Process password reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $hashed_password = hash('sha256', $password); // ← change to password_hash() later

        $sql = "UPDATE user SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE name = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $error = "Update prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $hashed_password, $user['name']);

            if ($stmt->execute()) {
                $success = 'Password has been reset successfully! You can now 
                            <a href="sign in.php" style="color:#c4a47c;">Sign In</a> 
                            with your new password.';
            } else {
                $error = 'Failed to update password. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .alert-box {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
        }
        .alert-danger  { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
    </style>
</head>
<body style="background-color: #f8f9fa; padding-top: 70px;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 card p-4 shadow">

                <?php if ($error): ?>
                    <div class="alert-box alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif ($success): ?>
                    <div class="alert-box alert-success">
                        <?= $success ?>
                    </div>
                <?php else: ?>

                    <h2 class="text-center mb-4">Set New Password</h2>
                    <p class="text-center text-muted mb-4">
                        Please enter and confirm your new password.
                    </p>

                    <form method="POST">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Enter new password" minlength="6" required>
                            <small class="form-text text-muted">At least 6 characters.</small>
                        </div>

                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="Confirm new password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" 
                                style="background:#c4a47c; border:none; padding:12px;">
                            Reset Password
                        </button>

                        <div class="text-center mt-3">
                            <a href="sign in.php" style="color:#c4a47c;">Back to Sign In</a>
                        </div>
                    </form>

                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>