<?php
session_start();
include 'db.php'; // only if you need DB connection here

// PHPMailer
require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_verification_code($name, $email, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shynayip913@gmail.com';
        // IMPORTANT: NEVER commit this to GitHub/public repo!
        $mail->Password   = 'splkzndhplhccemp'; // ← use .env in real project
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('shynayip913@gmail.com', 'Yob Yong Web');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code - Yob Yong Web';
        
        $mail->Body = "
        <h2>Hello {$name},</h2>
        <p>Thank you for registering with Yob Yong Web!</p>
        <p>Your verification code is:</p>
        <h1 style='color: #4CAF50; letter-spacing: 8px; font-family: monospace;'>{$code}</h1>
        <p>This code is valid for <strong>10 minutes</strong>.</p>
        <p>If you didn't request this, please ignore this email.</p>
        <br>
        <small>© " . date('Y') . " Yob Yong Web. All rights reserved.</small>
        ";

        $mail->AltBody = "Hello {$name},\n\nYour verification code is: {$code}\nValid for 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// ────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerbtn'])) {
    
    // Sanitize & validate
    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? ''; // ← add this field in form!

    if (!$email) {
        $_SESSION['status'] = "Please enter a valid email address.";
        header("Location: index.php");
        exit();
    }

    if (empty($name) || empty($phone) || strlen($password) < 6) {
        $_SESSION['status'] = "Name, phone and password (min 6 chars) are required.";
        header("Location: index.php");
        exit();
    }

    // Generate secure code
    $code = random_int(100000, 999999); // no need for sprintf

    // Store in session - use CONSISTENT naming!
    $_SESSION['verify_name']           = $name;
    $_SESSION['verify_email']          = $email;
    $_SESSION['verify_phone']          = $phone;
    $_SESSION['verify_password']       = $password;               // ← plain for now
    // or better: $_SESSION['verify_password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    
    $_SESSION['verify_code']           = $code;                    // ← important: consistent name!
    $_SESSION['code_generated_at']     = time();

    // Send email
    if (send_verification_code($name, $email, $code)) {
        $_SESSION['status'] = "Verification code sent to your email!";
        header("Location: verify-email.php");
        exit();
    } else {
        $_SESSION['status'] = "Failed to send verification email. Please try again.";
        header("Location: index.php");
        exit();
    }
}

// Optional: Only for debugging - remove later!
// Uncomment when you want to check session values
/*
echo "<pre style='background:#000;color:#0f0;padding:20px;'>";
echo "Session debug after registration attempt:\n\n";
var_dump($_SESSION);
echo "</pre>";
die();
*/
?>