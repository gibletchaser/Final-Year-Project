<?php
// START SESSION (only if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CONNECT TO DATABASE
$servername = "localhost";
$username = "root"; // your MySQL username
$password = "";     // your MySQL password
$dbname = "yibyong";

$conn = new mysqli($servername, $username, $password, $dbname);

// CHECK CONNECTION
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yob Yong Ordering System - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar (top info omitted for brevity) -->
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Yob Yong</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            Hi, <?= htmlspecialchars($_SESSION['name']) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item cta"><a href="sign in.php" class="nav-link">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 text-center mb-4">
                <h1 class="mb-2 bread">Login To Account</h1>
                <p class="breadcrumbs"><a href="index.php">Home</a> / Sign In</p>
            </div>
        </div>
    </div>
</section>

<!-- Login Form -->
<section class="ftco-section ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 p-4 p-md-5 pt-5">
                <div class="heading-section mb-5">
                    <span class="subheading">Login Account</span>
                </div>
                <form id="loginForm">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="loginEmail" class="form-control" placeholder="Your Email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="loginPassword" class="form-control" placeholder="Your Password">
                    </div>
                    <div class="form-group mt-3">
                        <input type="button" onclick="loginUser()" value="Login" class="btn btn-primary py-3 px-5">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="ftco-footer ftco-bg-dark ftco-section"></footer>

<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
function loginUser() {
    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;

    if(!email || !password) {
        alert("Please fill in email and password");
        return;
    }

    const data = new FormData();
    data.append("email", email);
    data.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(res => {
        if(res === "success") {
            window.location.href = "index.php";
        } else if(res === "missing") {
            alert("Please fill all fields");
        } else {
            alert("Invalid email or password");
        }
    });
}
</script>

</body>
</html>
