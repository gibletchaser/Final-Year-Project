<?php
include("db.php");

$id = $_GET['id'];

// prevent deleting admin account (optional safety)
$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$row = mysqli_fetch_assoc($check);

if($row['role'] == "admin"){
    echo "<script>alert('You cannot delete an admin account!'); window.location='staffList.php';</script>";
    exit();
}

$sql = "DELETE FROM users WHERE id='$id'";

if(mysqli_query($conn, $sql)){
    echo "<script>alert('Staff Deleted Successfully!'); window.location='staffList.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
