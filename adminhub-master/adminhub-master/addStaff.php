<?php
include("db.php");

if(isset($_POST['addStaff'])){

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password']; // keep plain text since your DB already uses it
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Check if username already exists
    $checkUser = "SELECT * FROM users WHERE username='$username'";
    $resultUser = mysqli_query($conn, $checkUser);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $resultEmail = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($resultUser) > 0){
        echo "<script>alert('Username already exists!');</script>";
    }
    else if(mysqli_num_rows($resultEmail) > 0){
        echo "<script>alert('Email already exists!');</script>";
    }
    else {

        $sql = "INSERT INTO users (full_name, username, password, role, email)
                VALUES ('$full_name', '$username', '$password', '$role', '$email')";

        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Staff Added Successfully!'); window.location='staffList.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>

    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Yobyong Admin</span>
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
                    <i class='bx bxs-food-menu'></i>
                    <span class="text">Menu</span>
                </a>
            </li>

            <li class="active">
                <a href="staffList.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Staff</span>
                </a>
            </li>

            <li>
                <a href="viewFeedback.php">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Feedback</span>
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
            <a href="#" class="nav-link">Add Staff</a>
        </nav>
        <!-- NAVBAR -->


        <!-- MAIN -->
        <main>

            <div class="head-title">
                <div class="left">
                    <h1>Add Staff</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="staffList.php">Staff</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="addStaff.php">Add Staff</a></li>
                    </ul>
                </div>
            </div>


            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Staff Information</h3>
                    </div>

                    <form method="POST">

                        <label>Full Name</label><br>
                        <input type="text" name="full_name" required
                        style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Username</label><br>
                        <input type="text" name="username" required
                        style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Password</label><br>
                        <input type="text" name="password" required
                        style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Email</label><br>
                        <input type="email" name="email" required
                        style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Role</label><br>
                        <select name="role" required
                        style="width:100%; padding:10px; margin-bottom:15px;">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select><br>

                        <button type="submit" name="addStaff"
                        style="padding:10px 20px; background:#3C91E6; color:white; border:none; cursor:pointer; border-radius:5px;">
                            Add Staff
                        </button>

                        <a href="staffList.php"
                        style="padding:10px 20px; background:gray; color:white; text-decoration:none; border-radius:5px;">
                            Cancel
                        </a>

                    </form>

                </div>
            </div>

        </main>
        <!-- MAIN -->

    </section>
    <!-- CONTENT -->

<script src="script.js"></script>
</body>
</html>
