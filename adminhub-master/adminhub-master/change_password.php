<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {

        $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "User not found.";
        } else {
            if ($old_password !== $user['password']) {
                $error = "Old password is incorrect.";
            } else {

                $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
                $stmt->bind_param("ss", $new_password, $username);

                if ($stmt->execute()) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "Failed to update password.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">

    <title>Change Password</title>
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">AdminHub</span>
    </a>

    <ul class="side-menu top">
        <li>
            <a href="index.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="myStore.php">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">My Store</span>
            </a>
        </li>

        <li class="active">
            <a href="change_password.php">
                <i class='bx bxs-lock'></i>
                <span class="text">Change Password</span>
            </a>
        </li>

        <li>
            <a href="profile.php">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

<!-- CONTENT -->
<section id="content">

    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Change Password</a>
    </nav>

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Change Password</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Change Password</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order">

                <h3>Update Password</h3>

                <?php if ($error): ?>
                    <p style="color:red; margin-top:10px;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <?php if ($success): ?>
                    <p style="color:green; margin-top:10px;"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <form method="POST" style="margin-top: 20px; max-width: 400px;">
                    <label>Old Password</label>
                    <input type="password" name="old_password" required style="width:100%; padding:10px; margin-bottom:15px;">

                    <label>New Password</label>
                    <input type="password" name="new_password" required style="width:100%; padding:10px; margin-bottom:15px;">

                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required style="width:100%; padding:10px; margin-bottom:15px;">

                    <button type="submit" class="btn-download">
                        <i class='bx bxs-save'></i>
                        <span class="text">Save Password</span>
                    </button>
                </form>

            </div>
        </div>

    </main>
</section>

<script src="script.js"></script>
</body>
</html>
