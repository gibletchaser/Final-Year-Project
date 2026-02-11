<?php
session_start();
include("db.php");


$id = $_GET['id'];

$sql = "SELECT * FROM reviews WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['sendReply'])){

    $reply = $_POST['reply'];

    $update = "UPDATE reviews 
               SET reply='$reply', status='Replied', replied_at=NOW()
               WHERE id='$id'";

    if(mysqli_query($conn, $update)){
        echo "<script>alert('Reply Sent Successfully!'); window.location='viewFeedback.php';</script>";
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
    <title>Reply Feedback</title>

    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

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
            <a href="logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>


<section id="content">

    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Reply Feedback</a>
    </nav>

    <main>

        <div class="head-title">
            <div class="left">
                <h1>Reply Feedback</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a href="viewFeedback.php">Feedback</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Reply</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Reply to Customer</h3>
                </div>

                <p><b>Name:</b> <?php echo $row['reviewer_name']; ?></p>
                <p><b>Email:</b> <?php echo $row['reviewer_email']; ?></p>
                <p><b>Rating:</b> <?php echo $row['rating']; ?>/5</p>
                <p><b>Comment:</b> <?php echo $row['comment']; ?></p>
                <p><b>Status:</b> <?php echo $row['status']; ?></p>

                <br>

                <label><b>Reply Template</b></label>
                <select style="width:100%; padding:10px; margin-top:10px;"
                    onchange="document.getElementById('replyBox').value=this.value;">
                    
                    <option value="">-- Choose Template --</option>

                    <option value="Hi <?php echo $row['reviewer_name']; ?>, thank you for your feedback. We apologize for the inconvenience. We will improve our service.">
                        General Apology
                    </option>

                    <option value="Hi <?php echo $row['reviewer_name']; ?>, thank you so much for your feedback! We are happy you enjoyed our service. Hope to see you again soon.">
                        Thank You Message
                    </option>

                    <option value="Hi <?php echo $row['reviewer_name']; ?>, thank you for your feedback. We are sorry to hear about the issue. Our team will take action to improve this matter.">
                        Service Improvement
                    </option>

                </select>

                <br><br>

                <form method="POST">

                    <label><b>Reply Message</b></label><br>
                    <textarea id="replyBox" name="reply" required rows="6"
                        style="width:100%; padding:10px; margin-top:10px;"><?php echo $row['reply']; ?></textarea>

                    <br><br>

                    <button type="submit" name="sendReply"
                        style="padding:10px 20px; background:#3C91E6; color:white; border:none; cursor:pointer; border-radius:5px;">
                        Send Reply
                    </button>

                    <a href="viewFeedback.php"
                        style="padding:10px 20px; background:gray; color:white; text-decoration:none; border-radius:5px;">
                        Cancel
                    </a>

                </form>

            </div>
        </div>

    </main>

</section>

<script src="script.js"></script>
</body>
</html>
