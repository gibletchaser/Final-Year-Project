<?php
require "db.php"; // Your database connection (assuming $conn is mysqli)

session_start(); // needed for flash messages

// Enable error reporting during development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... after require "db.php"; and session_start();

// Inside the POST handler:
$email = trim($_POST['email']);

$sql = "SELECT name, email FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $user_identifier = $customer['name'];   // ← this is your PK

    $reset_token = bin2hex(random_bytes(32));
    $token_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $update_sql = "UPDATE customer SET reset_token = ?, reset_token_expires = ? WHERE name = ?";
    $update = $conn->prepare($update_sql);

    if ($update === false) {
        $_SESSION['flash'] = "System error: " . $conn->error;
        $_SESSION['flash_type'] = "error";
    } else {
        $update->bind_param("sss", $reset_token, $token_expires, $user_identifier);

        if ($update->execute()) {
            // Your email sending code here...
            // Example using plain mail():
            $reset_link = "http://localhost/yyos/Final-Year-Project/feliciano-master/reset-password.php?token=" . urlencode($reset_token);

            $subject = "Reset Your Password";
            $message = "Click here to reset: " . $reset_link . "\n\nLink expires in 1 hour.";
            $headers = "From: no-reply@yourdomain.com";

           // After successfully storing the token...

require_once 'mailer.php';  // ← or 'app/mailer.php' if you placed it there

if (sendPasswordResetEmail($email, $customer['name'], $reset_token)) {
    $_SESSION['flash'] = 'Password reset link has been sent to your email!';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash'] = 'Failed to send reset email. Please try again later.';
    $_SESSION['flash_type'] = 'error';
}
        } else {
            $_SESSION['flash'] = "Failed to create reset token.";
            $_SESSION['flash_type'] = "error";
        }
    }
} else {
    $_SESSION['flash'] = "If an account exists with that email, you will receive a reset link.";
    $_SESSION['flash_type'] = "info";
}

header("Location: forgot-password.php");
exit;
}

// ────────────────────────────────────────────────
//           Show flash message if exists
// ────────────────────────────────────────────────
$flash = '';
$flash_type = '';
if (!empty($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    $flash_type = $_SESSION['flash_type'];
    unset($_SESSION['flash']);
    unset($_SESSION['flash_type']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .flash {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .flash-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .flash-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body style="background-color: #f8f9fa; padding-top: 70px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 card p-4 shadow">

                <?php if ($flash): ?>
                    <div class="flash flash-<?= htmlspecialchars($flash_type) ?>">
                        <?= htmlspecialchars($flash) ?>
                    </div>
                <?php endif; ?>

                <h2 class="text-center mb-4">Reset Password</h2>
                <p class="text-center text-muted mb-4">
                    Enter your registered email and we'll send you a link to reset your password.
                </p>

                <form method="POST">
                    <div class="form-group">
                        <label>Your Registered Email</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" 
                            style="background: #c4a47c; border: none; padding: 12px;">
                        Send Reset Link
                    </button>

                    <div class="text-center mt-3">
                        <a href="sign in.php" style="color: #c4a47c;">Back to Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


