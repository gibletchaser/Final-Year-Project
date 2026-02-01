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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerbtn'])) {
    
    // 1. Sanitize input to prevent SQL injection
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone    = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 2. Validate basic requirements
    if (empty($name) || empty($phone) || empty($email) || strlen($password) < 6) {
        $_SESSION['status'] = "All fields required. Password must be 6+ chars.";
        header("Location: index.php");
        exit();
    }

    // 3. DUPLICATE CHECK (The Fix)
    $check_query = "SELECT email, name, phone FROM user WHERE email='$email' OR name='$name' OR phone='$phone' LIMIT 1";
    $check_run = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_run) > 0) {
        $row = mysqli_fetch_assoc($check_run);
        if($row['email'] == $email) $_SESSION['status'] = "Error: Email already registered.";
        elseif($row['phone'] == $phone) $_SESSION['status'] = "Error: Phone number already in use.";
        else $_SESSION['status'] = "Error: Username already taken.";
        
        header("Location: index.php"); // Send back to index.php
        exit();
    }

    // 4. If we reached here, no duplicate was found. Now generate code and send email.
    $code = random_int(100000, 999999);
    $_SESSION['verify_name']     = $name;
    $_SESSION['verify_email']    = $email;
    $_SESSION['verify_phone']    = $phone;
    $_SESSION['verify_password'] = $password;
    $_SESSION['verify_code']     = $code;
    $_SESSION['code_generated_at'] = time();

    if (send_verification_code($name, $email, $code)) {
        $_SESSION['status'] = "Verification code sent to your email!";
        header("Location: verify-email.php");
        exit();
    } else {
        $_SESSION['status'] = "Mailer Error. Please try again.";
        header("Location: index.php");
        exit();
    }
}
?>