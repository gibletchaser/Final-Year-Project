<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$username = $_SESSION['username'] ?? '';

$stmt = $conn->prepare("SELECT username, full_name, email, role, created_at FROM users WHERE username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // Added error checking to debug prepare() failure
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">

</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">AdminHub</span>
    </a>
    <ul class="side-menu top">
        			<li class="active">
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
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
            <li>
				<a href="#">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Analytics</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Team</span>
				</a>
			</li>
		</ul>
	<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
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
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="profile.php" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->


<!-- CONTENT -->
<section id="content">
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

        <div class="table-data">
            <div class="order">
                <h3>Account Information</h3>
                <table>
                    <tr>
                        <th>Username</th>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                    </tr>
                    <tr>
                        <th>Account Created</th>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
</section>

<script src="script.js"></script>
</body>
</html>