<?php
session_start();
include("db.php");

$sql = "SELECT * FROM reviews ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>

    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <script>
		if (localStorage.getItem('darkMode') === 'enabled') {
			document.body.classList.add('dark');
		}
	</script>

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
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">My Store</span>
            </a>
        </li>

        <li>
            <a href="profile.php">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
            </a>
        </li>

        <li>
            <a href="staffList.php">
                <i class='bx bxs-group'></i>
                <span class="text">Staff</span>
            </a>
        </li>

        <li class="active">
            <a href="viewFeedback.php">
                <i class='bx bxs-message-dots'></i>
                <span class="text">Feedback</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="#">
                <i class='bx bxs-cog'></i>
                <span class="text">Settings</span>
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
        <a href="#" class="nav-link">Feedback</a>
        <form>
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button class="search-btn"><i class='bx bx-search'></i></button>
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


    <!-- MAIN -->
    <main>

        <div class="head-title">
            <div class="left">
                <h1>Feedback</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="viewFeedback.php">Feedback</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Customer Feedback</h3>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Reply</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['reviewer_name']; ?></td>
                            <td><?php echo $row['reviewer_email']; ?></td>
                            <td><?php echo $row['rating']; ?>/5</td>
                            <td><?php echo $row['comment']; ?></td>
                            <td>
                                <?php 
                                    if($row['status'] == "Replied"){
                                        echo "<span style='color:green; font-weight:bold;'>Replied</span>";
                                    } else {
                                        echo "<span style='color:red; font-weight:bold;'>Pending</span>";
                                    }
                                ?>
                            </td>
                            <td><?php echo $row['reply']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>

                            <td>
                                <a href="replyFeedback.php?id=<?php echo $row['id']; ?>"
                                   style="background:#3C91E6; padding:6px 12px; color:white; border-radius:5px; text-decoration:none;">
                                   Reply
                                </a>

                                <a href="deleteFeedback.php?id=<?php echo $row['id']; ?>"
                                   onclick="return confirm('Are you sure you want to delete this feedback?');"
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
                            <td colspan="9" style="text-align:center;">No feedback found.</td>
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
