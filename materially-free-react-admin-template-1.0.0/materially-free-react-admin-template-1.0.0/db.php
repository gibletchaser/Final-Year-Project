    <?php
$conn = new mysqli("localhost", "root", "", "yob_yong");

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
