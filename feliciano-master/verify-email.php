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
    $_SESSION['status'] = "Code expired. Please register again.";
    header("Location: index.php");
    exit();
}

if (isset($_POST['verifybtn'])) {
    $entered = trim($_POST['code']);
    $stored  = (string)$_SESSION['verify_code'];

    if ($entered === $stored) {
        // GET DATA FROM SESSION
        $name     = $_SESSION['verify_name'];
        $email    = $_SESSION['verify_email'];
        $phone    = $_SESSION['verify_phone'];
        $password = $_SESSION['verify_password'];

        // INSERT INTO DATABASE
        $query = "INSERT INTO user (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
        $query_run = mysqli_query($conn, $query);

        if ($query_run) {
            unset($_SESSION['verify_name'], $_SESSION['verify_email'], $_SESSION['verify_phone'], $_SESSION['verify_password'], $_SESSION['verify_code']);
            $_SESSION['status'] = "Registration Successful! Please Sign In.";
            header("Location: sign in.php");
            exit();
        } else {
            $_SESSION['status'] = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['status'] = "Invalid code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); text-align: center; }
        input { font-size: 20px; padding: 10px; width: 150px; text-align: center; margin: 10px 0; }
        button { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Verify Your Email</h2>
    <p>Code sent to: <b><?= $_SESSION['verify_email'] ?></b></p>
    
    <?php if(isset($_SESSION['status'])) { echo "<p style='color:red;'>".$_SESSION['status']."</p>"; unset($_SESSION['status']); } ?>

    <form method="POST">
        <input type="text" name="code" placeholder="123456" required>
        <br>
        <button type="submit" name="verifybtn">Verify & Register</button>
    </form>
</div>
</body>
</html>