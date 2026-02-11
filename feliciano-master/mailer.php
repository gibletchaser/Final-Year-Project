<?php
// mailer.php

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendPasswordResetEmail($email, $name, $reset_token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shynayip913@gmail.com';           // ← your Gmail
        $mail->Password   = 'splkzndhplhccemp';                // ← App Password (not normal password!)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('shynayip913@gmail.com', 'Yob Yong Web');
        $mail->addAddress($email, $name);                      // recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password - Yob Yong';

        $reset_link = "http://localhost/yyos/Final-Year-Project/feliciano-master/reset-password.php?token=" . urlencode($reset_token);

        $mail->Body    = "
        <h2>Password Reset Request</h2>
        <p>Hello $name,</p>
        <p>Click the button below to reset your password:</p>
        <p style='margin: 20px 0;'>
            <a href='$reset_link' style='
                background-color: #c4a47c;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            '>Reset Password</a>
        </p>
        <p>This link will expire in 1 hour.</p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <br>
        <p>Regards,<br>Yob Yong Team</p>";

        $mail->AltBody = "Hello $name,\n\nReset link: $reset_link\n\nExpires in 1 hour.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
        return false;
    }
}