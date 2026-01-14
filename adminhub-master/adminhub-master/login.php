<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare(
        "SELECT username, password FROM users WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ⚠️ plain-text comparison (your current setup)
        if ($password === $row['password']) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $row['username']; // ✅ NOW IT EXISTS

            header("Location: index.php");
            exit;
        }
    }

    $error = "Invalid login";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

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
            transition: 0.3s;
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
            transition: 0.4s;
        }

        .neon h2 {
            color: #00f7ff;
            text-shadow: 0 0 10px #00f7ff;
        }

        .neon input {
            border-color: #00f7ff;
            box-shadow: 0 0 8px rgba(0,247,255,0.4);
        }

        .neon button {
            background: #00f7ff;
            color: #000;
            box-shadow: 0 0 15px #00f7ff;
        }

        .toggle {
            position: absolute;
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 80px;
            background: #222;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .toggle span {
            background: #444;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle.on span {
            background: #00f7ff;
            transform: translateY(20px);
            box-shadow: 0 0 10px #00f7ff;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .forgot {
        text-align: center;
        margin-top: 12px;
        }

        .forgot a {
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
        }

        .neon .forgot a {
        color: #00f7ff;
        text-shadow: 0 0 8px #00f7ff;
        }

        .forgot a:hover {
        text-decoration: underline;
        }

    </style>
    
</head>
<body>

<div class="container">
    <form id="loginForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        <input type="password" name="password" placeholder="Password" required>

        <p class="forgot">
        <a href="forgot_password.php">Forgot Password?</a>
        </p>


        <button>Login</button>
    </form>

    

    <div class="toggle" id="toggle">
        <span></span>
    </div>
</div>

<script>
    const toggle = document.getElementById("toggle");
    const form = document.getElementById("loginForm");

    toggle.addEventListener("click", () => {
        toggle.classList.toggle("on");
        form.classList.toggle("neon");
    });
</script>
</body>
</html>