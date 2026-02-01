<?php
session_start();
include 'db.php';

if (!isset($_SESSION['verify_code'])) {
    header("Location: index.php");
    exit();
}

$minutes_passed = floor((time() - $_SESSION['code_generated_at']) / 60);
if ($minutes_passed >= 10) {
    session_destroy();
    session_start(); // Restart to carry the status message
    $_SESSION['status'] = "Code expired. Please register again.";
    header("Location: index.php");
    exit();
}

$fail = false; // Initialize the failure flag

if (isset($_POST['verifybtn'])) {
    $entered = trim($_POST['code']);
    $stored  = (string)$_SESSION['verify_code'];

    // --- CHECK 1: Is the code correct? ---
    if ($entered === $stored) {
        $name     = mysqli_real_escape_string($conn, $_SESSION['verify_name']);
        $email    = mysqli_real_escape_string($conn, $_SESSION['verify_email']);
        $phone    = mysqli_real_escape_string($conn, $_SESSION['verify_phone']);
        $password = mysqli_real_escape_string($conn, $_SESSION['verify_password']);

        $query = "INSERT INTO user (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
        $query_run = mysqli_query($conn, $query);

        if ($query_run) {
            unset($_SESSION['verify_name'], $_SESSION['verify_email'], $_SESSION['verify_phone'], $_SESSION['verify_password'], $_SESSION['verify_code']);
            $_SESSION['status'] = "Registration Successful! Please Sign In.";
            header("Location: sign in.php");
            exit();
        } else {
            $_SESSION['status'] = "Registration failed. Please try again.";
            header("Location: index.php");
            exit();
        }
    } 
    // --- THIS ELSE handles the wrong code ---
    else {
        $fail = true; // Triggers the Pop-out
        $_SESSION['status'] = "Invalid code. Please try again."; // Triggers the Red Text
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); text-align: center; }
        input { font-size: 20px; padding: 10px; width: 150px; text-align: center; margin: 10px 0; }
        button { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Verify Your Email</h2>
    <p>Code sent to: <b><?= htmlspecialchars($_SESSION['verify_email']) ?></b></p>
    
    <?php if ($fail): ?>
    <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
        <strong>Verification failed!</strong> The code you entered does not match. Please try again.
    </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['status'])): ?>
        <p style="color:red; font-size: 14px; margin-bottom: 15px;"><?= $_SESSION['status'] ?></p>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="code" placeholder="123456" required>
        <br>
        <button type="submit" name="verifybtn">Verify Email</button>
    </form>
</div>
</body>
</html>