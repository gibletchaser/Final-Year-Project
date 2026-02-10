<?php
include("db.php");

// fetch staff users only
$sql = "SELECT * FROM users WHERE role='staff' ORDER BY ID DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>

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
                <span class="text">My Store</span>
            </a>
        </li>

        <li>
            <a href="profile.php">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
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
        <a href="#" class="nav-link">Staff</a>
    </nav>
    <!-- NAVBAR -->



    <!-- MAIN -->
    <main>

        <div class="head-title">
            <div class="left">
                <h1>Staff List</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="staffList.php">Staff</a>
                    </li>
                </ul>
            </div>

            <a href="addStaff.php" class="btn-download">
                <i class='bx bx-plus'></i>
                <span class="text">Add Staff</span>
            </a>
        </div>



        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Staff Members</h3>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>

                            <td>
                                <a href="editStaff.php?id=<?php echo $row['ID']; ?>"
                                   style="background:orange; padding:6px 12px; color:white; border-radius:5px; text-decoration:none;">
                                   Edit
                                </a>

                                <a href="deleteStaff.php?id=<?php echo $row['ID']; ?>"
                                   onclick="return confirm('Are you sure you want to delete this staff?');"
                                   style="background:red; padding:6px 12px; color:white; border-radius:5px; text-decoration:none;">
                                   Delete
                                </a>
                            </td>
                        </tr>

                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No staff found.</td>
                        </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>

    </main>
    <!-- MAIN -->

</section>
<!-- CONTENT -->

<script src="script.js"></script>
</body>
</html>
