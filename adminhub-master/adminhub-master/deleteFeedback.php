<?php
session_start();
include("db.php");



$id = $_GET['id'];

$sql = "DELETE FROM reviews WHERE id='$id'";

if(mysqli_query($conn, $sql)){
    echo "<script>alert('Feedback Deleted Successfully!'); window.location='viewFeedback.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
