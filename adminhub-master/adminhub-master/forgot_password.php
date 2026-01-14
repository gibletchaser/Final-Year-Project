<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $error = "User not found";
        } else {
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
            $stmt->bind_param("ss", $new_password, $username);
            $stmt->execute();

            $success = "Password reset successful";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #000;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .container {
            position: relative;
        }

        form {
            background: #141414;
            padding: 35px;
            width: 320px;
            border-radius: 18px;
            transition: 0.4s;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 2px solid #555;
            padding: 10px;
            color: white;
            margin-bottom: 18px;
        }

        input:focus {
            outline: none;
            border-color: #00f7ff;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            background: #fff;
            font-weight: 500;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            text-align: center;
        }

        .success h2 {
            color: #00f7ff;
            text-shadow: 0 0 10px #00f7ff;
            margin-bottom: 10px;
        }

        .success p {
            margin-bottom: 15px;
            color: #ccc;
        }

        .success a {
            color: #00f7ff;
            text-decoration: none;
            font-weight: 500;
            text-shadow: 0 0 8px #00f7ff;
        }

        .success a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <form method="POST">
        <h2>Forgot Password</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success neon">
                <h2>Password reset successful</h2>
                <p>You may login now.</p>
                <a href="login.php">Back to Login</a>
            </div>
        <?php else: ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button>Reset Password</button>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
