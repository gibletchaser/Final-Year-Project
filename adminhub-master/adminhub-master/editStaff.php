<?php
include("db.php");

$id = $_GET['id'];

// fetch staff info
$sql = "SELECT * FROM users WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// if update button clicked
if(isset($_POST['updateStaff'])){

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = "UPDATE users SET 
                full_name='$full_name',
                username='$username',
                email='$email',
                role='$role'
               WHERE id='$id'";

    if(mysqli_query($conn, $update)){
        echo "<script>alert('Staff Updated Successfully!'); window.location='staffList.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>

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
            <a href="#" class="nav-link">Edit Staff</a>
        </nav>
        <!-- NAVBAR -->


        <!-- MAIN -->
        <main>

            <div class="head-title">
                <div class="left">
                    <h1>Edit Staff</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="staffList.php">Staff</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Edit Staff</a></li>
                    </ul>
                </div>
            </div>


            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Edit Staff Information</h3>
                    </div>

                    <form method="POST">

                        <label>Full Name</label><br>
                        <input type="text" name="full_name"
                               value="<?php echo $row['full_name']; ?>"
                               required
                               style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Username</label><br>
                        <input type="text" name="username"
                               value="<?php echo $row['username']; ?>"
                               required
                               style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Email</label><br>
                        <input type="email" name="email"
                               value="<?php echo $row['email']; ?>"
                               required
                               style="width:100%; padding:10px; margin-bottom:15px;"><br>

                        <label>Role</label><br>
                        <select name="role" required style="width:100%; padding:10px; margin-bottom:15px;">
                            <option value="staff" <?php if($row['role']=="staff") echo "selected"; ?>>Staff</option>
                            <option value="admin" <?php if($row['role']=="admin") echo "selected"; ?>>Admin</option>
                        </select><br>

                        <button type="submit" name="updateStaff"
                        style="padding:10px 20px; background:#3C91E6; color:white; border:none; cursor:pointer; border-radius:5px;">
                            Update Staff
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
