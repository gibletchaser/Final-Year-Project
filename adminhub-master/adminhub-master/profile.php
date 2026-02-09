<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$username = $_SESSION['username'] ?? '';

// HANDLE PROFILE UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $profilePic = $_FILES['profile_pic'] ?? null;

    $profileImageName = null;

    if ($profilePic && $profilePic['error'] === 0) {
        $ext = strtolower(pathinfo($profilePic['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $profileImageName = uniqid('profile_', true) . '.' . $ext;
            move_uploaded_file($profilePic['tmp_name'], 'uploads/' . $profileImageName);
        }
    }

    if ($profileImageName) {
        $stmt = $conn->prepare(
            "UPDATE users SET username = ?, profile_image = ? WHERE username = ?"
        );
        $stmt->bind_param("sss", $newUsername, $profileImageName, $username);
    } else {
        $stmt = $conn->prepare(
            "UPDATE users SET username = ? WHERE username = ?"
        );
        $stmt->bind_param("ss", $newUsername, $username);
    }

    $stmt->execute();
    $_SESSION['username'] = $newUsername;

    header("Location: profile.php");
    exit;
}

// FETCH USER DATA
$stmt = $conn->prepare(
    "SELECT username, full_name, email, role, created_at, profile_image 
     FROM users WHERE username = ?"
);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Profile | AdminHub</title>
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
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <form>
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>
        <a href="profile.php" class="profile">
            <img src="uploads/<?= htmlspecialchars($user['profile_image'] ?? 'people.png') ?>">
        </a>
    </nav>
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>My Profile</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Profile</a></li>
                </ul>
            </div>
        </div>

        <!-- PROFILE CARD CENTERED -->
        <div class="table-data">
            <div class="order">

                <div class="profile-card">
                    <form method="POST" enctype="multipart/form-data">

                        <div class="profile-header">
                            <img
                                src="uploads/<?= htmlspecialchars($user['profile_image'] ?? 'people.png') ?>"
                                class="profile-avatar"
                            >
                            <label class="upload-btn">
                                Change Photo
                                <input type="file" name="profile_pic" hidden>
                            </label>
                        </div>

                        <div class="profile-field">
                            <label>Username</label>
                            <input type="text" name="username"
                                   value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>

                        <div class="profile-field">
                            <label>Full Name</label>
                            <input type="text" value="<?= htmlspecialchars($user['full_name']) ?>" disabled>
                        </div>

                        <div class="profile-field">
                            <label>Email</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>

                        <div class="profile-field">
                            <label>Role</label>
                            <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled>
                        </div>

                        <div class="profile-field">
                            <label>Account Created</label>
                            <input type="text" value="<?= htmlspecialchars($user['created_at']) ?>" disabled>
                        </div>

                        <button type="submit" class="btn-save">
                            <i class='bx bxs-save'></i>
                            Save Changes
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->

<script src="script.js"></script>
</body>
</html>
