<?php
session_start();

// Protection: if no active verification process → back to registration
if (!isset(
    $_SESSION['verify_email'],
    $_SESSION['verify_name'],
    $_SESSION['verify_code'],             // ← must match registration
    $_SESSION['code_generated_at']
)) {
    header("Location: index.php");           // ← your registration page
    exit();
}

// Check expiration (10 minutes)
$minutes_passed = floor((time() - $_SESSION['code_generated_at']) / 60);
if ($minutes_passed >= 10) {
    unset(
        $_SESSION['verify_email'],
        $_SESSION['verify_name'],
        $_SESSION['verify_phone'],
        $_SESSION['verify_code'],
        $_SESSION['code_generated_at']
    );
    $_SESSION['status'] = "Verification code has expired. Please register again.";
    header("Location: index.php");
    exit();
}

// Handle verification attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifybtn'])) {
    $entered = trim($_POST['code'] ?? '');

    $entered_normalized  = str_pad($entered, 6, '0', STR_PAD_LEFT);
    $stored_normalized   = str_pad((string)$_SESSION['verify_code'], 6, '0', STR_PAD_LEFT);

    if ($entered_normalized === $stored_normalized) {
        // ── SUCCESS ───────────────────────────────────────────────

        $name  = $_SESSION['verify_name'];
        $email = $_SESSION['verify_email'];
        $phone = $_SESSION['verify_phone'] ?? null;

        // Optional: Save to database here
        // require 'dbcon.php';
        // $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, created_at) VALUES (?,?,?,NOW())");
        // $stmt->execute([$name, $email, $phone]);

        // Clean up session
        unset(
            $_SESSION['verify_email'],
            $_SESSION['verify_name'],
            $_SESSION['verify_phone'],
            $_SESSION['verify_code'],
            $_SESSION['code_generated_at']
        );

        $_SESSION['success'] = "Email verified successfully! Welcome, {$name}!";

        header("Location: sign in.php");   // ← or dashboard.php, home.php, etc.
        exit();
    } else {
        $_SESSION['status'] = "Invalid code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Yob Yong</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        h2 { margin: 0 0 1rem; color: #333; }
        .message { margin: 1rem 0; color: #555; }
        input {
            font-size: 2rem;
            width: 180px;
            text-align: center;
            letter-spacing: 0.6em;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 1rem 0;
        }
        button {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 14px 40px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover { background: #0b5ed7; }
        .error   { color: #dc3545; font-weight: bold; }
        .success { color: #198754; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter Verification Code</h2>
    <p class="message">We sent a 6-digit code to<br>
        <strong><?= htmlspecialchars($_SESSION['verify_email'] ?? 'your email') ?></strong>
    </p>

    <?php if (isset($_SESSION['status'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['status']) ?></p>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="code" maxlength="6" pattern="[0-9]*" inputmode="numeric"
               placeholder="123456" required autofocus>
        <br><br>
        <button type="submit" name="verifybtn">Verify Code</button>
    </form>

    <p style="margin-top: 1.5rem; color: #666; font-size: 0.95rem;">
        Code expires in <strong><?= max(0, 10 - $minutes_passed) ?></strong> minutes
    </p>
</div>

</body>
</html>